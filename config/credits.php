<?php

return [
   'default' => [
      'demands_valid_for' => 7,
      'urgence_status_count' => 0,
      'photos_demand_count' => 1,
      'offers_per_month' => 0,
      'offers_valid_for' => 0,
      'candidatures_count' => 15,
      'contracts_count' => 3,
   ],
   'basic' => [
      'demands_valid_for' => 15,
      'urgence_status_count' => 1,
      'photos_demand_count' => 3,
      'offers_per_month' => 3,
      'offers_valid_for' => 7,
      'candidatures_count' => 50,
      'contracts_count' => 10,
   ],
   'professionnel' => [
      'demands_valid_for' => 30,
      'urgence_status_count' => 3,
      'photos_demand_count' => 3,
      'offers_per_month' => 10,
      'offers_valid_for' => 15,
      'candidatures_count' => 150,
      'contracts_count' => 15,
   ],
   'premium' => [
      'demands_valid_for' => 90,
      'urgence_status_count' => -1,
      'photos_demand_count' => 5,
      'offers_per_month' => -1,
      'offers_valid_for' => 30,
      'candidatures_count' => -1,
      'contracts_count' => 30,
   ],
];
