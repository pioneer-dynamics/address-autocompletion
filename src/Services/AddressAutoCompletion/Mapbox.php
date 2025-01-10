<?php
namespace PioneerDynamics\AddressAutocomplete\Services\AddressAutoCompletion;

use PioneerDynamics\AddressAutocomplete\Contracts\AddressAutoCompletionProvider;

class Mapbox extends AddressAutoCompletionManager implements AddressAutoCompletionProvider
{
    public function getSuggestions($address): array
    {
        $response = $this->client->get('forward', [
            'q' => $address,
            'access_token' => $this->config['api_key'],
        ]);

        return $response->json('features');
    }

    public function mapSuggestions($suggestions): void
    {
        foreach ($suggestions as $suggestion) {
            $properties = $suggestion['properties'];

            $this->addAddress(
                latitude: $properties['coordinates']['latitude'] ?? '',
                longitude: $properties['coordinates']['longitude'] ?? '',
                country: $properties['context']['country']['name'] ?? '',
                country_code: $properties['context']['country']['country_code'] ?? '',
                street_number: $properties['context']['address']['address_number'] ?? '',
                street: $properties['context']['address']['street_name'] ?? '',
                city: $properties['context']['locality']['name'] ?? null,
                state: $properties['context']['region']['name'] ?? '',
                post_code: $properties['context']['postcode']['name'] ?? '',
                formatted_address: ($properties['context']['address']['address_number'] ?? '').' '. ($properties['context']['address']['street_name'] ?? '') .' '.  ($properties['place_formatted'] ?? ''),
            );
        }
    }
}