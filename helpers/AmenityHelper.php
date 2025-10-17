<?php
class AmenityHelper {
    
    /**
     * Get icon for amenity
     */
    public static function getAmenityIcon($amenity) {
        $icons = [
            'AC' => 'fas fa-snowflake',
            'WiFi' => 'fas fa-wifi',
            'Furnished' => 'fas fa-couch',
            'Meals' => 'fas fa-utensils',
            'Laundry' => 'fas fa-tshirt',
            'Security' => 'fas fa-shield-alt',
            'Housekeeping' => 'fas fa-broom',
            'Power Backup' => 'fas fa-bolt',
            'Gym' => 'fas fa-dumbbell',
            'Swimming Pool' => 'fas fa-swimming-pool',
            'Parking' => 'fas fa-parking',
            'Common Area' => 'fas fa-users',
            'Study Room' => 'fas fa-book',
            'Library' => 'fas fa-book-open',
            'CCTV' => 'fas fa-video',
            'Elevator' => 'fas fa-elevator',
            'Balcony' => 'fas fa-building',
            'Kitchen' => 'fas fa-fire',
            'Refrigerator' => 'fas fa-snowflake',
            'TV' => 'fas fa-tv',
            'Wardrobe' => 'fas fa-door-open',
            'Bed' => 'fas fa-bed',
            'Table' => 'fas fa-table',
            'Chair' => 'fas fa-chair',
            'Fan' => 'fas fa-fan',
            'Light' => 'fas fa-lightbulb',
            'Water' => 'fas fa-tint',
            'Bathroom' => 'fas fa-bath',
            'Toilet' => 'fas fa-toilet',
            'Mirror' => 'fas fa-mirror',
            'Geyser' => 'fas fa-fire',
            'Washing Machine' => 'fas fa-tshirt',
            'Iron' => 'fas fa-iron',
            'Medical' => 'fas fa-first-aid',
            'Transport' => 'fas fa-bus',
            'Metro' => 'fas fa-subway',
            'Market' => 'fas fa-shopping-cart',
            'Hospital' => 'fas fa-hospital',
            'ATM' => 'fas fa-credit-card',
            'Restaurant' => 'fas fa-utensils',
            'Cafe' => 'fas fa-coffee'
        ];
        
        return $icons[trim($amenity)] ?? 'fas fa-check';
    }
    
    /**
     * Get amenity color class
     */
    public static function getAmenityColor($amenity) {
        $colors = [
            'AC' => 'text-blue-500',
            'WiFi' => 'text-green-500',
            'Furnished' => 'text-purple-500',
            'Meals' => 'text-orange-500',
            'Laundry' => 'text-indigo-500',
            'Security' => 'text-red-500',
            'Housekeeping' => 'text-pink-500',
            'Power Backup' => 'text-yellow-500',
            'Gym' => 'text-gray-600',
            'Swimming Pool' => 'text-blue-400',
            'Parking' => 'text-gray-500',
            'Common Area' => 'text-green-600',
            'Study Room' => 'text-blue-600',
            'Library' => 'text-purple-600'
        ];
        
        return $colors[trim($amenity)] ?? 'text-green-500';
    }
    
    /**
     * Render amenity with icon
     */
    public static function renderAmenity($amenity, $showIcon = true, $extraClasses = '') {
        $icon = self::getAmenityIcon($amenity);
        $color = self::getAmenityColor($amenity);
        
        $html = '<span class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-200 ' . $extraClasses . '">';
        
        if ($showIcon) {
            $html .= '<i class="' . $icon . ' ' . $color . ' mr-1"></i>';
        }
        
        $html .= htmlspecialchars(trim($amenity)) . '</span>';
        
        return $html;
    }
    
    /**
     * Render amenities list
     */
    public static function renderAmenitiesList($amenities, $limit = null, $showIcons = true) {
        if (empty($amenities)) {
            return '';
        }
        
        $amenitiesArray = is_string($amenities) ? json_decode($amenities, true) : $amenities;
        if (!is_array($amenitiesArray)) {
            return '';
        }
        
        $displayAmenities = $limit ? array_slice($amenitiesArray, 0, $limit) : $amenitiesArray;
        $html = '<div class="flex flex-wrap gap-2">';
        
        foreach ($displayAmenities as $amenity) {
            $html .= self::renderAmenity($amenity, $showIcons);
        }
        
        // Show "more" indicator if limited
        if ($limit && count($amenitiesArray) > $limit) {
            $remaining = count($amenitiesArray) - $limit;
            $html .= '<span class="bg-primary-50 text-primary-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-primary-200">';
            $html .= '+' . $remaining . ' more</span>';
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Render location with standard format
     */
    public static function renderLocation($address = null, $city = null, $pincode = null) {
        // Use default location if not provided
        $defaultAddress = "456 Cyber City, Gurgaon";
        $defaultNote = "Map integration coming soon";
        
        $displayAddress = $address ?: $defaultAddress;
        $displayCity = $city ?: '';
        $displayPincode = $pincode ?: '';
        
        $fullAddress = $displayAddress;
        if ($displayCity && $displayCity !== 'Gurgaon') {
            $fullAddress .= ', ' . $displayCity;
        }
        if ($displayPincode) {
            $fullAddress .= ' - ' . $displayPincode;
        }
        
        $html = '<div class="flex items-start gap-2 text-gray-600 mb-4">';
        $html .= '<i class="fas fa-map-marker-alt text-primary-600 mt-1 flex-shrink-0"></i>';
        $html .= '<div>';
        $html .= '<p class="text-sm">' . htmlspecialchars($fullAddress) . '</p>';
        if (!$address || $address === $defaultAddress) {
            $html .= '<p class="text-xs text-gray-500 italic">' . $defaultNote . '</p>';
        }
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}
?>
