<?php
require_once '../src/Choice/restaurantChoice.php';

/**
 * Class to test restaurantChoice.
 */
class restaurantChoiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * The object to test.
     *
     * @var restaurantChoice
     */
    private $obj;

    /**
     * Initialize the object to test.
     */
    public function setUp()
    {
        $this->obj = new restaurantChoice();
    }

    /**
     * Tests, that if we don't pass restaurants should return empty string.
     */
    public function testWhenNoPassRestaurants()
    {
        $this->assertEmpty( $this->obj->getRestaurant( array(), array(), '' ),
            'When we not pass restaurants should return empty string.' );
    }

    /**
     * Tests, that if we don't pass restaurants should return empty string.
     */
    public function testWhenNoPassVotes()
    {
        $restaurants = array(
            'Japo' => array( 'arroz' ),
            'Pizzeria' => array( '4 quesos' )
        );
        $this->assertEquals( 'Japo', $this->obj->getRestaurant( $restaurants, array(),'' ),
            'When we not pass votes, the result should be the first alphabetical' );
    }

    /**
     * Tests than when all the votes are positives, and not is draw, the result is correct.
     */
    public function testWhenAllVotesArePositivesAndNotDraw()
    {
        $restaurants = array(
            'Japo'          => array( 'shushi', 'salmon', 'arroz' ),
            'Pizzeria 1'    => array( 'italiana', 'pasta', 'bacon', 'jamon' ),
            'Pizzeria 2'    => array( 'argentina', 'jamon', 'piña' )

        );
        $choice = array(
            'User 1'    => array( 'shushi' => 1, 'salmon' => 1 ),
            'User 2'    => array( 'italiana' => 1, 'pasta' => 1, 'jamon' => 1 )
        );
        $organizer = 'User 1';

        $this->assertEquals( 'Pizzeria 1', $this->obj->getRestaurant( $restaurants, $choice, $organizer ),
            'Test than when exists votes, and there are not draw, should return the restaurant with more votes positives' );
    }

    /**
     * Tests than when all the votes are positives, and not is draw, the result is correct.
     */
    public function testWhenAllVotesArePositivesAndHasDrawTiebreakerByOrganizer()
    {
        $restaurants = array(
            'Japo'          => array( 'shushi', 'salmon', 'arroz' ),
            'Pizzeria 1'    => array( 'italiana', 'pasta', 'bacon', 'jamon' ),
            'Pizzeria 2'    => array( 'argentina', 'jamon', 'piña' )

        );
        $choice = array(
            'User 2'    => array( 'shushi' => 1, 'salmon' => 1 ),
            'User 1'    => array( 'italiana' => 1, 'pasta' => 1 )
        );
        $organizer = 'User 1';

        $this->assertEquals( 'Pizzeria 1', $this->obj->getRestaurant( $restaurants, $choice, $organizer ),
            'Test than when exists votes, and there are draw, should return the restaurant with more votes positives from organizer' );
    }

    /**
     * Tests than when all the votes are positives, and not is draw, the result is correct.
     */
    public function testWhenAllVotesArePositivesAndHasDrawDrawByOrganizer()
    {
        $restaurants = array(
            'Japo'          => array( 'shushi', 'salmon', 'arroz' ),
            'Pizzeria 1'    => array( 'italiana', 'pasta', 'bacon', 'jamon' ),
            'Pizzeria 2'    => array( 'argentina', 'jamon', 'piña' )

        );
        $choice = array(
            'User 2'    => array( 'pasta' => 1, 'salmon' => 1 ),
            'User 1'    => array( 'italiana' => 1, 'shushi' => 1 )
        );
        $organizer = 'User 1';

        $this->assertEquals( 'Japo', $this->obj->getRestaurant( $restaurants, $choice, $organizer ),
            'Test than when exists votes, and there are draw for organizer too, should return the first alphabetical.' );
    }

    /**
     * Tests than when all the votes are positives, and not is draw, the result is correct.
     */
    public function testWhenWithAllTypeOfVotes()
    {
        $restaurants = array(
            'Japo'          => array( 'shushi', 'salmon', 'arroz' ),
            'Pizzeria 1'    => array( 'italiana', 'pasta', 'bacon', 'jamon' ),
            'Pizzeria 2'    => array( 'argentina', 'jamon', 'piña' )

        );
        $choice = array(
            'User 2'    => array( 'pasta' => 1, 'salmon' => -1 ),
            'User 1'    => array( 'italiana' => 1, 'shushi' => 1 )
        );
        $organizer = 'User 1';

        $this->assertEquals( 'Pizzeria 1', $this->obj->getRestaurant( $restaurants, $choice, $organizer ),
            'Test than when exists all type of votes, the result is ok.' );
    }

}
