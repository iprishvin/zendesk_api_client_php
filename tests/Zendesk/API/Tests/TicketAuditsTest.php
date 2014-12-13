<?php

namespace Zendesk\API\Tests;

use Zendesk\API\Client;

/**
 * Ticket Audits test class
 */
class TicketAuditsTest extends BasicTest {
    
    public function testCredentials() {
        parent::credentialsTest();
    }

    public function testAuthToken() {
        parent::authTokenTest();
    }

    /**
     * @depends testAuthToken
     */
    public function testAll() {
        $audits = $this->client->ticket(2)->audits()->findAll();
        $this->assertEquals(is_object($audits), true, 'Should return an object');
        $this->assertEquals(is_array($audits->audits), true, 'Should return an object containing an array called "audits"');
        $this->assertGreaterThan(0, $audits->audits[0]->id, 'Returns a non-numeric id in first audit');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
    }

    /**
     * @depends testAuthToken
     */
    public function testAllSideLoadedMethod() {
        $audits = $this->client->ticket(2)->sideload(array('users', 'groups'))->audits()->findAll();
        $this->assertEquals(is_object($audits), true, 'Should return an object');
        $this->assertEquals(is_array($audits->users), true, 'Should return an object containing an array called "users"');
        $this->assertEquals(is_array($audits->groups), true, 'Should return an object containing an array called "groups"');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
    }

    /**
     * @depends testAuthToken
     */
    public function testAllSideLoadedParameter() {
        $audits = $this->client->ticket(2)->audits()->findAll(array('sideload' => array('users', 'groups')));
        $this->assertEquals(is_object($audits), true, 'Should return an object');
        $this->assertEquals(is_array($audits->users), true, 'Should return an object containing an array called "users"');
        $this->assertEquals(is_array($audits->groups), true, 'Should return an object containing an array called "groups"');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
    }

    /**
     * @depends testAuthToken
     */
    public function testFind() {
        $audits = $this->client->ticket(2)->audit(16317679361)->find(); // ticket #2 must never be deleted, nor audit #16317679361
        $this->assertEquals(is_object($audits), true, 'Should return an object');
        $this->assertEquals(is_object($audits->audit), true, 'Should return an object containing an array called "audit"');
        $this->assertEquals('16317679361', $audits->audit->id, 'Returns an incorrect id in audit object');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
    }

    /*
     * Test mark as trusted
     */
    public function testMarkAsTrusted() {
        $audits = $this->client->ticket(2)->audit(16317679361)->markAsTrusted();
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '200', 'Does not return HTTP code 200');
    }

}

?>
