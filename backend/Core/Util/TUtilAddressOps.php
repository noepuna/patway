<?php

/**
 * ...
 *
 * @category	Core\Util
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
	namespace Core\Util;

	//use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
	//use CommerceGuys\Addressing\Country\CountryRepository;
	//use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;

	trait TUtilAddressOps
	{
		public function t_UtilAddressOps_isValid( string $ADDRESS, string $TYPE ) : bool
		{
			return false;
		}

		/*public static function t_UtilAddressOps_isValid( $EMAIL )
		{
			$countryRepository = new CountryRepository();
			$addressFormatRepository = new AddressFormatRepository();
			$subdivisionRepository = new SubdivisionRepository();

			// Get the country list (countryCode => name), in French.
			$countryList = $countryRepository->getList('fr-FR');

			// Get the country object for Brazil.
			$brazil = $countryRepository->get('BR');
			echo $brazil->getThreeLetterCode(); // BRA
			echo $brazil->getName(); // Brazil
			echo $brazil->getCurrencyCode(); // BRL
			print_r($brazil->getTimezones());

			// Get all country objects.
			$countries = $countryRepository->getAll();

			// Get the address format for Brazil.
			$addressFormat = $addressFormatRepository->get('BR');

			// Get the subdivisions for Brazil.
			$states = $subdivisionRepository->getAll(['BR']);
			foreach ($states as $state) {
			    $municipalities = $state->getChildren();
			}

			// Get the subdivisions for Brazilian state Ceará.
			$municipalities = $subdivisionRepository->getAll(['BR', 'CE']);
			foreach ($states as $state)
			{
			    echo $state->getName() . "<br />";
			}
		}*/
	}

?>