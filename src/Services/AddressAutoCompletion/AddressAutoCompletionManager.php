<?php
namespace PioneerDynamics\AddressAutocomplete\Services\AddressAutoCompletion;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use PioneerDynamics\AddressAutocomplete\Contracts\AddressAutoCompletionEngine;


abstract class AddressAutoCompletionManager implements AddressAutoCompletionEngine
{
    protected ?PendingRequest $client;
    protected ?Collection $addresses;

    public function __construct(protected array $config) 
    {
        $this->client = Http::baseUrl($this->config['endpoint']);

        $this->addresses = collect([]);

        app()->call([$this, 'initialize']);
    }

    public function autocomplete(string $address): self
    {
        $suggestions = app()->call([$this, 'getSuggestions'], ['address' => $address]);

        $this->clearAddresses();

        app()->call([$this, 'mapSuggestions'], ['suggestions' => $suggestions]);

        return $this;
    }

    public function reverseGeocode(float $latitude, float $longitude): self
    {
        $suggestions = app()->call([$this, 'getAddressFromCoordinates'], ['latitude' => $latitude, 'longitude' => $longitude]);

        $this->clearAddresses();

        app()->call([$this, 'mapSuggestions'], ['suggestions' => $suggestions]);

        return $this;
    }

    public function initialize()
    {
        // placeholder
    }

    protected function addAddress($latitude, $longitude, $country, $country_code, $street_number, $street, $city, $state, $post_code, $formatted_address)
    {
        $this->addresses->push([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'country' => $country,
            'country_code' => $country_code,
            'number' => $street_number,
            'street' => $street,
            'city' => $city,
            'state' => $state,
            'post_code' => $post_code,
            'formatted_address' => $formatted_address,
        ]);
    }

    /**
     * Get addresses
     * 
     * @return Collection<$latitude, $longitude, $country, $country_code, $number, $street, $state, $post_code>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    protected function clearAddresses()
    {
        $this->addresses = collect([]);
    }
}