<?php
/**
 * @author j
 * 
 * @group App
 */
import('seleniumtestcase');

class Test_All extends PHPUnit_Extensions_SeleniumTestCase {
    protected function setUp() {
        $this->setBrowser("*chrome");
        $this->setBrowserUrl("http://localhost/fuelphp/public");
    }
    
    public function testMyTestCase() {
        $this->open("/fuelphp/public/");
        $this->click("link=Sign In");
        $this->waitForPageToLoad("30000");
        $this->sendKeys("id=form_username", "J");
        $this->sendKeys("id=form_password", "1234");
        $this->click("id=form_submit");
        $this->waitForPageToLoad("30000");
        $this->click("link=Add New Message");
        $this->waitForPageToLoad("30000");
        $this->sendKeys("id=form_title", "J");
        $this->type("id=form_message", "新增訊息");
        $this->click("id=form_submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=i.icon-trash");
        $this->assertTrue((bool)preg_match('/^Are you sure[\s\S]$/',$this->getConfirmation()));
        $this->click("link=Sign Out");
        $this->waitForPageToLoad("30000");
        $this->click("link=Sign In");
        $this->waitForPageToLoad("30000");
        $this->sendKeys("id=form_username", "admin");
        $this->sendKeys("id=form_password", "admin");
        $this->click("id=form_submit");
        $this->waitForPageToLoad("30000");
        $this->click("link=Add New Message");
        $this->waitForPageToLoad("30000");
        $this->sendKeys("id=form_title", "admin");
        $this->type("id=form_message", "新增訊息");
        $this->click("id=form_submit");
        $this->waitForPageToLoad("30000");
        $this->click("//a[3]/i");
        $this->assertTrue((bool)preg_match('/^Are you sure[\s\S]$/',$this->getConfirmation()));
        $this->click("link=View User Logs");
        $this->waitForPageToLoad("30000");
        $this->sendKeys("id=form_username", "J");
        $this->click("id=form_submit");
        $this->waitForPageToLoad("30000");
        $this->sendKeys("id=form_username", "admin");
        $this->click("id=form_submit");
        $this->waitForPageToLoad("30000");
        $this->click("link=Back");
        $this->waitForPageToLoad("30000");
        $this->click("link=View Message Logs");
        $this->waitForPageToLoad("30000");
        $this->sendKeys("id=form_action", "D");
        $this->sendKeys("id=form_username", "admin");
        $this->click("id=form_submit");
        $this->waitForPageToLoad("30000");
        $this->click("link=Back");
        $this->waitForPageToLoad("30000");
        $this->click("link=Sign Out");
        $this->waitForPageToLoad("30000");
    }
}
