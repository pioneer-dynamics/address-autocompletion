<?php
namespace PioneerDynamics\AddressAutocomplete\Services\AddressAutoCompletion;

use PioneerDynamics\AddressAutocomplete\Contracts\AddressAutoCompletionProvider;

class Radar extends AddressAutoCompletionManager implements AddressAutoCompletionProvider
{
    public function initialize()
    {
        $this->client->withHeader('Authorization', $this->config['api_key']);
    }

    public function getSuggestions($address): array
    {
        $response = $this->client->get('', [
            'query' => $address,
        ]);

        return $response->json('addresses');
    }

    public function mapSuggestions($suggestions): void
    {
        foreach ($suggestions as $address) {
            $this->addAddress(
                latitude: $address['latitude'] ?? '',
                longitude: $address['longitude'] ?? '',
                country: $address['country'] ?? '',
                country_code: $address['countryCode'] ?? '',
                street_number: $address['number'] ?? '',
                street: $address['street'] ?? '',
                city: $address['city'] ?? '',
                state: $address['state'] ?? '',
                post_code: $address['postalCode'] ?? '',
                formatted_address: $address['formattedAddress'] ?? '',
            );
        }
    }
}