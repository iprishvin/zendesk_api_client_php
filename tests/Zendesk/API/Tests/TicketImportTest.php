<?php

namespace Zendesk\API\Tests;

use Zendesk\API\Client;

/**
 * Ticket Imports test class
 */
class TicketImportTest extends BasicTest {

    public function testCredentials() {
        parent::credentialsTest();
    }

    public function testAuthToken() {
        parent::authTokenTest();
    }

    protected $author_id;
    
    public function setUP(){
        $user = $this->client->users()->create(array(
            'name' => 'Roger Wilco',
            'email' => 'roge@example.org',
            'role' => 'agent',
            'verified' => true
        ));
        $this->author_id = $user->user->id;
    }

    public function tearDown(){
        $this->client->user($this->author_id)->delete();
    }

    public function testImport() {
        $confirm = $this->client->tickets()->import(array(
            'subject' => 'Help',
            'description' => 'A description',
            'comments' => array(
                array('author_id' => $this->author_id, 'value' => 'This is a author comment') // 454094082 is me
            )
        ));
        $this->assertEquals(is_object($confirm), true, 'Should return an object');
        $this->assertEquals(is_object($confirm->ticket), true, 'Should return an object called "ticket"');
        $this->assertGreaterThan(0, $confirm->ticket->id, 'Returns a non-numeric id for ticket');
        $this->assertEquals($confirm->ticket->subject, 'Help', 'Subject of test ticket does not match');
        $this->assertEquals($confirm->ticket->description, 'A description', 'Description of test ticket does not match');
        $this->assertEquals($this->client->getDebug()->lastResponseCode, '201', 'Does not return HTTP code 201');
    }

}

?>
