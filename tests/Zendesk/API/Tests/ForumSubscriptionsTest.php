<?php

namespace Zendesk\API\Tests;

use Zendesk\API\Client;

/**
 * ForumSubscriptions test class
 */
class ForumSubscriptionsTest extends BasicTest {

    public function testCredentials() {
        parent::credentialsTest();
    }

    public function testAuthToken() {
        parent::authTokenTest();
    }

    protected $forum_id, $user_id, $id;
    
    public function setUp(){
        $forum = $this->client->forums()->create(array(
            'name' => 'My Forum',
            'forum_type' => 'articles',
            'access' => 'logged-in users',
        ));

        $this->forum_id = $forum->forum->id;

        $user = $this->client->users()->create(array(
            'name' => 'Roger Wilco',
            'email' => 'roge@example.org',
            'role' => 'agent',
            'verified' => true
        ));
        $this->user_id = $user->user->id;

        $forumSubscription = $this->client->forum($this->forum_id)->subscriptions()->create(array(
            'user_id' => $this->user_id
        ));

        $this->assertEquals(is_object($forumSubscription), true, 'Should return an object');
        $this->assertEquals(is_object($forumSubscription->forum_subscription), true, 'Should return an object called "forum_subscription"');
        $this->assertGreaterThan(0, $forumSubscription->forum_subscription->id, 'Returns a non-numeric id for forum_subscription');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '201', 'Does not return HTTP code 201');
        $this->id = $forumSubscription->forum_subscription->id;
    }
    
    public function tearDown(){
        $this->assertGreaterThan(0, $this->id, 'Cannot find a forum subscription id to test with. Did testCreate fail?');
        $view = $this->client->forums()->subscription($this->id)->delete();
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200'); 

        $this->client->forum($this->forum_id)->delete();
        $this->client->user($this->user_id)->delete();
    }

    public function testAll() {
        $forumSubscriptions = $this->client->forums()->subscriptions()->findAll();
        $this->assertEquals(is_object($forumSubscriptions), true, 'Should return an object');
        $this->assertEquals(is_array($forumSubscriptions->forum_subscriptions), true, 'Should return an object containing an array called "forum_subscriptions"');
        $this->assertGreaterThan(0, $forumSubscriptions->forum_subscriptions[0]->id, 'Returns a non-numeric id for forum_subscriptions[0]');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
    }

    public function testFind() {
        $forumSubscription = $this->client->forums()->subscription($this->id)->find(); // find the one we just created
        $this->assertEquals(is_object($forumSubscription), true, 'Should return an object');
        $this->assertGreaterThan(0, $forumSubscription->forum_subscription->id, 'Returns a non-numeric id for forum_subscription');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
    }
}

?>
