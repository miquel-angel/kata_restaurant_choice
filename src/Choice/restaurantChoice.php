<?php
/**
 * Class restaurantChoice, to obtain the restaurant with the most value for a group of friends.
 */
class restaurantChoice
{

    /**
     * Given a list of restaurant with a keywords, and the user votes for N keywords, return the most voted restaurant.
     *
     * For example, you can have the restaurant "Pizzeria in the Street X" with the keywords, "pasta, spaghetti", and
     * a user can vote, positive or negative this keywords.
     *
     * @param array $candidates_restaurants The list of restaurants with their keywords.
     * @param array $user_votes The list of votes for each user.
     * @param string $organizer In case of draw, the votes of the organizer, has more value.
     * @return array
     */
    public function getRestaurant( $candidates_restaurants, $user_votes, $organizer )
    {
        if ( empty( $candidates_restaurants ) )
        {
            return array();
        }

        $votes_restaurants = $this->initVotes($candidates_restaurants);

        foreach ( $user_votes as $user => $votes )
        {
            $extra_vote_organizer = ( $user === $organizer );

            $votes_restaurants = $this->assignUserVotes(
                $candidates_restaurants, $votes, $votes_restaurants, $extra_vote_organizer
            );
        }

        usort( $votes_restaurants, array( $this, 'orderRestaurant' ) );

        return $votes_restaurants[0]['restaurant'];
    }

    /**
     * Update with the votes of a concrete user.
     *
     * @param array $candidates_restaurants The list of restaurants with the keywords.
     * @param array $votes The votes of the current user.
     * @param array $votes_restaurants The total votes for each restaurant currently.
     * @param boolean $extra_vote_organizer Indicate if the user that are votting is the organizer.
     * @return array
     */
    public function assignUserVotes( $candidates_restaurants, $votes, $votes_restaurants, $extra_vote_organizer )
    {
        foreach ( $candidates_restaurants as $keys )
        {
            $votes_for_restaurant = array_intersect( array_keys( $votes ), $keys );

            $votes_total = 0;

            foreach ( $votes_for_restaurant as $vote )
            {
                $votes_total += $votes[$vote];
            }

            $votes_restaurants[$keys['position_in_array']]['votes_global'] += $votes_total;

            if ( $extra_vote_organizer )
            {
                $votes_restaurants[$keys['position_in_array']]['votes_organizer'] += $votes_total;
            }
        }

        return $votes_restaurants;
    }

    /**
     * Function used to order the restaurant, the first criteria is the global votes, if draw the restaurant with more
     * votes of the organizer, and finally, if also draw, the first alphabetical.
     *
     * @param array $value_a The first value to order.
     * @param array $value_b The second value to order.
     * @return boolean
     */
    private function orderRestaurant( array $value_a, array $value_b )
    {
        if ( $value_a['votes_global'] < $value_b['votes_global'] )
        {
            return true;
        }
        if ( $value_a['votes_global'] > $value_b['votes_global'] )
        {
            return false;
        }
        if ( $value_a['votes_organizer'] < $value_b['votes_organizer'] )
        {
            return true;
        }
        if ( $value_a['votes_organizer'] > $value_b['votes_organizer'] )
        {
            return false;
        }


        return $value_a['restaurant'] > $value_b['restaurant'];
    }

    /**
     * Get the list of restaurants with the default values, and add in the list of candidate the position
     * that the restaurant is in this new array.
     *
     * @param array $candidates_restaurants The list of restaurants.
     * @return array
     */
    private function initVotes( &$candidates_restaurants )
    {
        $default_value = array(
            'votes_organizer' => 0,
            'votes_global' => 0
        );
        $votes_restaurants = array();
        $position_in_array = 0;

        foreach ($candidates_restaurants as $restaurant => $keys)
        {
            $votes_restaurants[] = array_merge( $default_value, array( 'restaurant' => $restaurant ) );
            $candidates_restaurants[$restaurant]['position_in_array'] = $position_in_array++;
        }
        return $votes_restaurants;
    }
}
?>
