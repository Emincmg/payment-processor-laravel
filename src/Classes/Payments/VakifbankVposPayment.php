<?php

class VakifbankVposPayment extends Payment
{
    public function process(): void
    {
        // TODO: Implement process() method.
    }

    public function success(): void
    {
        // TODO: Implement success() method.
    }

    public function fail(): void
    {
        // TODO: Implement fail() method.
    }

    /**
     * Handle VPos enrollment callback and process payment.
     *
     * @param VPosEnrollment $enrollment
     * @param array $vposRequestData
     * @return string|array The result of the payment process ('success' or an array containing error data).
     * @throws Exception If enrollment data is not found.
     */
    public function processVPosCallback(VPosEnrollment $enrollment, array $vposRequestData): string|array
    {
        $enrollment->ECI = $vposRequestData['Eci'];
        $enrollment->CAVV = $vposRequestData['Cavv'];
        $enrollment->save();

        $paymentData = new PaymentData(
            channel: 'vpos',
            amount: $enrollment->purchaseAmount,
            currency: $enrollment->currency,
            userName: $enrollment->CardHoldersName,
            userEmail: 'info@frezyahotel.com',
            userPhone: '+90555555555',
            userIp: $enrollment->userIp,
            paymentMethod: 'card',
            confirmationMethod: 'automatic',
            enrollment: $enrollment
        );

        $result = $this->processPayment($paymentData);
        $payment = $this->storePayment($paymentData);

        if ($result === 'success') {
            $this->handleSuccess($payment);
        } else {
            $this->handleFailure($payment);
        }
        return $result;
    }

    /**
     * Determine the brand name code for VPOS Enrollment Request based on the credit card number (PAN).
     *
     * This method uses regular expressions to determine the card's brand based on its number.
     * - 100: Visa (starts with 4)
     * - 200: MasterCard (starts with 51 to 55)
     * - 300: Troy (starts with 9792 or 65)
     *
     * @param string $pan The credit card number (PAN) used to determine the brand.
     * @return int The brand name code to be sent in the VPOS request.
     *
     * @throws \Exception If the card brand cannot be determined.
     */
    public function getBrandNameCode(string $pan): int
    {
        if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $pan)) {
            return 100; // Visa
        } elseif (preg_match('/^5[1-5][0-9]{14}$/', $pan)) {
            return 200; // MasterCard
        } elseif (preg_match('/^9792[0-9]{12}$/', $pan)) {
            return 300; // Troy (old regex)
        } elseif (preg_match('/^65[0-9]{14,}$/', $pan)) {
            return 300; // Troy (new regex)
        }

        throw new \Exception("Card brandcode is not supported.");
    }

    /**
     * Create a new VPosEnrollment object based on validated input data.
     *
     * This method creates a new 3D Secure enrollment request object, with the necessary card and transaction details
     * for further processing. It formats the card details, converts the currency, and generates a unique
     * verifyEnrollmentRequestId for the enrollment.
     *
     * @param array $validated The validated request data containing card details, currency, and user information.
     * @param string $userIp The IP address of the user who is making the request.
     *
     * @return VPosEnrollment A new instance of VPosEnrollment ready to be saved to the database.
     *
     * @throws \Exception If there is an error during currency conversion or price data retrieval.
     */
    public function createEnrollment(array $validated, string $userIp): VPosEnrollment
    {
        $priceData = $this->getPriceCache($validated['price_key']);
        $cardHolderName = $validated['user_data']['name'] . ' ' . $validated['user_data']['lastname'];
        $currencyCode = $validated['currency'];
        $verifyEnrollmentRequestId = Str::random(16);
        $currencyConverter = new ConvertCodeToCurrency($currencyCode, $validated['channel']);
        $currency = $currencyConverter->convert();
        $rawPrice = $priceData[$validated['currency']];
        $cleanedPrice = str_replace(',', '.', $rawPrice);
        $numericPrice = floatval(preg_replace('/[^0-9.]/', '', $cleanedPrice));
        if ($validated['currency'] === 'EUR' ) {
            $amount = intval(round($numericPrice * 100));
        } elseif ($validated['currency'] === 'TRY') {
            $amount = intval(round($numericPrice * 1000));
        }
        $formattedAmount = number_format($amount, 2, '.', '');
        $formattedPan = str_replace(' ', '', $validated['pan']);
        $explodedExpiryDate = explode('/', $validated['expiryDate']);
        $formattedExpiryDate = $explodedExpiryDate[1] . $explodedExpiryDate[0];
        $brandName = self::getBrandNameCode($formattedPan);

        return new VPosEnrollment([
            'verifyEnrollmentRequestId' => $verifyEnrollmentRequestId,
            'pan' => $formattedPan,
            'expiryDate' => $formattedExpiryDate,
            'cvv' => $validated['cvv'],
            'purchaseAmount' => $formattedAmount,
            'currency' => $currency,
            'brandName' => $brandName,
            'CardHoldersName' => $cardHolderName,
            'userIp' => $userIp,
            'status' => 'new',
            'sessionInfo' => json_encode($validated),
        ]);
    }

    /**
     * Send the 3D Secure enrollment request to the VPOS system.
     *
     * This method sends the previously created `VPosEnrollment` object to the VPOS system for enrollment processing.
     * It returns the updated `VPosEnrollment` object with any response data from the VPOS system.
     *
     * @param VPosEnrollment $enrollment The enrollment object containing all necessary data to be sent.
     *
     * @return VPosEnrollment The updated enrollment object with response data from the VPOS system.
     *
     * @throws \Exception If there is an error while sending the request or processing the response.
     */
    public function sendEnrollmentRequest(VPosEnrollment $enrollment): VPosEnrollment
    {
        return $enrollment->sendEnrollmentRequest();
    }

    /**
     * Save the VPosEnrollment object to the database.
     *
     * This method saves the given `VPosEnrollment` object to the database and returns the saved instance.
     *
     * @param VPosEnrollment $enrollment The enrollment object to be saved.
     *
     * @return VPosEnrollment The saved enrollment object.
     */
    public function saveEnrollment(VPosEnrollment $enrollment): VPosEnrollment
    {
        $enrollment->save();
        return $enrollment;
    }

    /**
     * Delete the given VPosEnrollment instance.
     *
     * @param VPosEnrollment $enrollment The enrollment instance to be deleted.
     *
     * @return void
     */
    public function deleteEnrollment(VPosEnrollment $enrollment): void
    {
        $enrollment->delete();
    }
}