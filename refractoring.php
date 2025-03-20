<?php
class CompanyClass
{

    public function normalizeCompanyData(array $data): ?array
    {
        $companyData = [
            'name' => null,
            'website' => null,
            'address' => null
        ];


        if (!$this->isCompanyDataValid($data)) {
            return null;
        }

        $companyData['name'] = $this->nameNormalizer($data["name"]);
        $companyData['website'] = $this->websiteNormalizer($data['website']);
        $companyData['address'] = $this->addressNormalizer($data['address']);


        if ($companyData['website'] == null) {
            unset($companyData['website']);
        }
        if (isset($data['address']))
            $companyData['address'] = trim($data['address']);


        if (empty($companyData['address'])) {
            $companyData['address'] = null;
        }
        return $companyData;
    }


    private function isCompanyDataValid(array $data): bool
    {
        return isset($data['name']) && isset($data['address']); //checks
    }

    private function nameNormalizer(?string $importedData): ?string{
        $importedData = strtolower(trim($importedData));
        return $importedData;
    }

    private function websiteNormalizer(?string $importedData): ?string{
        if ($importedData == null) {
            return null;
        }

        $normalizedWebsite = trim($importedData);

        return (preg_match('/^https?:\/\//', $normalizedWebsite))
            ? parse_url($normalizedWebsite, PHP_URL_HOST)
            : $normalizedWebsite;
    }

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