<?php
/**
 * @group Message
 * @author j
 *
 */
class Test_Message extends TestCase
{
    public function test_foo()
    {
        $entry = Model_Message::get_all(array());
        
        $this->assertEmpty(!$entry);
    }
}