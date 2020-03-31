<?php

require_once(__DIR__ . '/lib/MappIntelligence.php');

function trackingTest()
{
    $page = new MappIntelligencePage(/*'en.checkout.onepage.success'*/);
    $page->setCategory(1, 'checkout.onepage.success');
    $page->setCategory(2, 'en');
    $page->setCategory(3, 'checkout');
    $page->setCategory(4, 'onepage');
    $page->setCategory(5, 'success');

    $order = new MappIntelligenceOrder(360.93);

    $customer = new MappIntelligenceCustomer('24');
    $customer->setFirstName('John');
    $customer->setLastName('Doe');
    $customer->setEmail('john@doe.com');

    $session = new MappIntelligenceSession();
    $session->setParameter(1, '1');

    $product = new MappIntelligenceProduct('065ee2b001');
    $product->setCost(59.99);
    $product->setQuantity(1);
    $product->setStatus('conf');

    $product2 = new MappIntelligenceProduct('085eo2f009');
    $product2->setCost(49.99);
    $product2->setQuantity(5);
    $product2->setStatus('conf');

    $product3 = new MappIntelligenceProduct('995ee1k906');
    $product3->setCost(15.99);
    $product3->setQuantity(1);
    $product3->setStatus('conf');

    $webtrekk = MappIntelligence::getInstance(array(
        'trackId' => '123451234512345',
        //'trackId' => '111111111111111',
        'trackDomain' => 'q3.webtrekk.net',
        'cookie' => '1',
        'debug' => true,

        //'consumer' => 'curl',
        //'consumer' => 'fork-curl',
        'consumer' => 'file',
        'maxBatchSize' => 500,
        'filename' => dirname(__FILE__) . '/tmp/webtrekk.log',

        'useParamsForDefaultPageName' => array('sc', 'foo')
    ));

    $webtrekk->track(array(
        'page' => $page,
        'order' => $order,
        'customer' => $customer,
        'session' => $session,
        'product' => array($product, $product2, $product3)
    ));
}

function trackingTest2()
{
    $webtrekk = MappIntelligence::getInstance(array(
        'trackId' => '123451234512345',
        'trackDomain' => 'q3.webtrekk.net',
        'cookie' => '1'
    ));

    $webtrekk->track(array(
        'pn' => 'en.checkout.onepage.success',
        'cg1' => 'checkout.onepage.success',
        'cg2' => 'en',
        'cg3' => 'checkout',
        'cg4' => 'onepage',
        'cg5' => 'success',
        'oi' => 360.93,
        'cd' => '24',
        'uc500' => 'John',
        'uc501' => 'Doe',
        'uc502' => 'john@doe.com',
        'cs1' => '1',
        'ba' => '065ee2b001;085eo2f009;995ee1k906',
        'co' => '59.99;49.99;15.99',
        'qn' => '1;5;1',
        'st' => 'conf;conf;conf'
    ));
}

trackingTest();
trackingTest2();
