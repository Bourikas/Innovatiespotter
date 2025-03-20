<?php
/**
 * Class CompanyClass
 *
 * Handles normalization of company data by formatting the name, website, and address fields.
 */
class CompanyClass
{
    /**
     * Normalizes the company data.
     *
     * - Converts the company name to lowercase and trims whitespace.
     * - Extracts the domain name from a valid website URL.
     * - Trims whitespace from the address.
     * - Removes the website field if it's null.
     *
     * @param array $data The input company data with 'name', 'website', and 'address'.
     * @return array|null Returns normalized company data, or null if validation fails.
     */
    public function normalizeCompanyData(array $data): ?array
    {
        // Initialize array with default null values
        $companyData = [
            'name' => null,
            'website' => null,
            'address' => null
        ];

        // Validate data before processing
        if (!$this->isCompanyDataValid($data)) {
            return null;
        }

        // Normalize fields
        $companyData['name'] = $this->nameNormalizer($data["name"]);
        $companyData['website'] = $this->websiteNormalizer($data['website']);
        $companyData['address'] = $this->addressNormalizer($data['address']);

        // Remove website if it's null
        if ($companyData['website'] == null) {
            unset($companyData['website']);
        }

        // Ensure address is set properly
        if (empty($companyData['address'])) {
            $companyData['address'] = null;
        }
        return $companyData;
    }

    /**
     * Validates if the required company data fields exist.
     *
     * @param array $data The input data array.
     * @return bool Returns true if both 'name' and 'address' are set, false otherwise.
     */
    private function isCompanyDataValid(array $data): bool
    {
        return isset($data['name']) && isset($data['address']); //checks
    }

    /**
     * Normalizes the company name.
     *
     * - Converts to lowercase.
     * - Trims whitespace.
     *
     * @param string|null $importedData The raw company name.
     * @return string|null The formatted company name.
     */
    private function nameNormalizer(?string $importedData): ?string{
        $importedData = strtolower(trim($importedData));
        return $importedData;
    }

    /**
     * Normalizes the company website.
     *
     * - Trims whitespace.
     * - Extracts the domain name if the website starts with 'http://' or 'https://'.
     * - Otherwise, returns the original input.
     *
     * @param string|null $importedData The raw website URL.
     * @return string|null The normalized website domain or original input.
     */
    private function websiteNormalizer(?string $importedData): ?string{
        if ($importedData == null) {
            return null;
        }

        $normalizedWebsite = trim($importedData);

        return (preg_match('/^https?:\/\//', $normalizedWebsite))
            ? parse_url($normalizedWebsite, PHP_URL_HOST)
            : $normalizedWebsite;
    }

    /**
     * Normalizes the company address.
     *
     * - Trims whitespace.
     *
     * @param string|null $importedData The raw address input.
     * @return string|null The formatted address.
     */
    private function addressNormalizer(?string $importedData): ?string{
        return trim($importedData);
    }

}



// Test Data
$input = [
    'name' => ' OpenAI ',
    'website' => 'https://openai.com ',
    'address' => ' '
];
$input2 = [
    'name' => 'Innovatiespotter',
    'address' => 'Groningen'
];
$input3 = [
    'name' => ' Apple ',
    'website' => 'xhttps://apple.com ',
];
$company = new CompanyClass();

$result = $company->normalizeCompanyData($input);
var_dump($result);

$result2 = $company->normalizeCompanyData($input2);
var_dump($result2);

$result3 = $company->normalizeCompanyData($input3);
var_dump($result3);