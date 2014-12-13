<?php

namespace Zendesk\API\Tests;

use Zendesk\API\Client;

/**
 * Automations test class
 */
class AutomationsTest extends BasicTest {

    public function testCredentials() {
        parent::credentialsTest();
    }

    public function testAuthToken() {
        parent::authTokenTest();
    }

    /**
     * @depends testAuthToken
     */
    public function testCreate() {
        $automation = $this->client->automations()->create(array(
            'title' => 'Roger Wilco',
            'all' => array(
                array(
                    'field' => 'status',
                    'operator' => 'is',
                    'value' => 'open'
                ),
                array(
                    'field' => 'priority',
                    'operator' => 'less_than',
                    'value' => 'high'
                )
            ),
            'actions' => array(
                array(
                    'field' => 'priority',
                    'value' => 'high'
                )
            )
        ));
        $this->assertEquals(is_object($automation), true, 'Should return an object');
        $this->assertEquals(is_object($automation->automation), true, 'Should return an object called "automation"');
        $this->assertGreaterThan(0, $automation->automation->id, 'Returns a non-numeric id for automation');
        $this->assertEquals($automation->automation->title, 'Roger Wilco', 'Name of test automation does not match');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '201', 'Does not return HTTP code 201');
        $id = $automation->automation->id;
        $stack = array($id);
        return $stack;
    }

    /**
     * @depends testCreate
     */
    public function testAll($stack) {
        $automations = $this->client->automations()->findAll();
        $this->assertEquals(is_object($automations), true, 'Should return an object');
        $this->assertEquals(is_array($automations->automations), true, 'Should return an object containing an array called "automations"');
        $this->assertGreaterThan(0, $automations->automations[0]->id, 'Returns a non-numeric id for automations[0]');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
        return $stack;
    }

    /**
     * @depends testCreate
     */
    public function testActive($stack) {
        $automations = $this->client->automations()->findAll(array('active' => true));
        $this->assertEquals(is_object($automations), true, 'Should return an object');
        $this->assertEquals(is_array($automations->automations), true, 'Should return an object containing an array called "automations"');
        $this->assertGreaterThan(0, $automations->automations[0]->id, 'Returns a non-numeric id for automations[0]');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
        return $stack;
    }

    /**
     * @depends testCreate
     */
    public function testFind($stack) {
        $id = array_pop($stack);
        $automation = $this->client->automations($id)->find();
        $this->assertEquals(is_object($automation), true, 'Should return an object');
        $this->assertGreaterThan(0, $automation->automation->id, 'Returns a non-numeric id for automation');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
        $stack = array($id);
        return $stack;
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(array $stack) {
        $id = array_pop($stack);
        $automation = $this->client->automation($id)->update(array(
            'title' => 'Roger Wilco II'
        ));
        $this->assertEquals(is_object($automation), true, 'Should return an object');
        $this->assertEquals(is_object($automation->automation), true, 'Should return an object called "automation"');
        $this->assertGreaterThan(0, $automation->automation->id, 'Returns a non-numeric id for automation');
        $this->assertEquals($automation->automation->title, 'Roger Wilco II', 'Name of test automation does not match');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
        $stack = array($id);
        return $stack;
    }

    /**
     * @depends testCreate
     */
    public function testDelete(array $stack) {
        $id = array_pop($stack);
        $this->assertGreaterThan(0, $id, 'Cannot find a automation id to test with. Did testCreate fail?');
        $topic = $this->client->automation($id)->delete();
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
    }

}

?>
