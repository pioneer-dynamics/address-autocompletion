<?php
namespace PioneerDynamics\AddressAutocomplete\Contracts;

use Illuminate\Support\Collection;

interface AddressAutoCompletionEngine
{
    public function autocomplete(string $address): self;

    /**
     * Get addresses
     * 
     * @return Collection<$latitude, $longitude, $country, $country_code, $number, $street, $state, $post_code>
     */
    public function getAddresses(): Collection;
}