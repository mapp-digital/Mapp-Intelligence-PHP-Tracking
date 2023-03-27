<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceConsumerCurlTest
 */
class MappIntelligenceConsumerCurlTest extends MappIntelligenceExtendsTestCase
{
    private $longText;
    private $contentMaxBatchSize_;
    private $maxPayloadSize_;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->longText = '';
        $this->longText .= 'Lorem%20ipsum%20dolor%20sit%20amet%2C%20consetetur%20sadipscing%20elitr%2C%20sed%20diam%20';
        $this->longText .= 'nonumy%20eirmod%20tempor%20invidunt%20ut%20labore%20et%20dolore%20magna%20aliquyam%20erat%';
        $this->longText .= '2C%20sed%20diam%20voluptua.%20At%20vero%20eos%20et%20accusam%20et%20justo%20duo%20dolores%';
        $this->longText .= '20et%20ea%20rebum.%20Stet%20clita%20kasd%20gubergren%2C%20no%20sea%20takimata%20sanctus%20';
        $this->longText .= 'est%20Lorem%20ipsum%20dolor%20sit%20amet.%20Lorem%20ipsum%20dolor%20sit%20amet%2C%20conset';
        $this->longText .= 'etur%20sadipscing%20elitr%2C%20sed%20diam%20nonumy%20eirmod%20tempor%20invidunt%20ut%20lab';
        $this->longText .= 'ore%20et%20dolore%20magna%20aliquyam%20erat%2C%20sed%20diam%20voluptua.%20At%20vero%20eos%';
        $this->longText .= '20et%20accusam%20et%20justo%20duo%20dolores%20et%20ea%20rebum.%20Stet%20clita%20kasd%20gub';
        $this->longText .= 'ergren%2C%20no%20sea%20takimata%20sanctus%20est%20Lorem%20ipsum%20dolor%20sit%20amet.%20Lo';
        $this->longText .= 'rem%20ipsum%20dolor%20sit%20amet%2C%20consetetur%20sadipscing%20elitr%2C%20sed%20diam%20no';
        $this->longText .= 'numy%20eirmod%20tempor%20invidunt%20ut%20labore%20et%20dolore%20magna%20aliquyam%20erat%2C';
        $this->longText .= '%20sed%20diam%20voluptua.%20At%20vero%20eos%20et%20accusam%20et%20justo%20duo%20dolores%20';
        $this->longText .= 'et%20ea%20rebum.%20Stet%20clita%20kasd%20gubergren%2C%20no%20sea%20takimata%20sanctus%20es';
        $this->longText .= 't%20Lorem%20ipsum%20dolor%20sit%20amet.%20Duis%20autem%20vel%20eum%20iriure%20dolor%20in%2';
        $this->longText .= '0hendrerit%20in%20vulputate%20velit%20esse%20molestie%20consequat%2C%20vel%20illum%20dolor';
        $this->longText .= 'e%20eu%20feugiat%20nulla%20facilisis%20at%20vero%20eros%20et%20accumsan%20et%20iusto%20odi';
        $this->longText .= 'o%20dignissim%20qui%20blandit%20praesent%20luptatum%20zzril%20delenit%20augue%20duis%20dol';
        $this->longText .= 'ore%20te%20feugait%20nulla%20facilisi.%20Lorem%20ipsum%20dolor%20sit%20amet%2C%20consectet';
        $this->longText .= 'uer%20adipiscing%20elit%2C%20sed%20diam%20nonummy%20nibh%20euismod%20tincidunt%20ut%20laor';
        $this->longText .= 'eet%20dolore%20magna%20aliquam%20erat%20volutpat.%20Ut%20wisi%20enim%20ad%20minim%20veniam';
        $this->longText .= '%2C%20quis%20nostrud%20exerci%20tation%20ullamcorper%20suscipit%20lobortis%20nisl%20ut%20a';
        $this->longText .= 'liquip%20ex%20ea%20commodo%20consequat.%20Duis%20autem%20vel%20eum%20iriure%20dolor%20in%2';
        $this->longText .= '0hendrerit%20in%20vulputate%20velit%20esse%20molestie%20consequat%2C%20vel%20illum%20dolor';
        $this->longText .= 'e%20eu%20feugiat%20nulla%20facilisis%20at%20vero%20eros%20et%20accumsan%20et%20iusto%20odi';
        $this->longText .= 'o%20dignissim%20qui%20blandit%20praesent%20luptatum%20zzril%20delenit%20augue%20duis%20dol';
        $this->longText .= 'ore%20te%20feugait%20nulla%20facilisi.%20Nam%20liber%20tempor%20cum%20soluta%20nobis%20ele';
        $this->longText .= 'ifend%20option%20congue%20nihil%20imperdiet%20doming%20id%20quod%20mazim%20placerat%20face';
        $this->longText .= 'r%20possim%20assum.%20Lorem%20ipsum%20dolor%20sit%20amet%2C%20consectetuer%20adipiscing%20';
        $this->longText .= 'elit%2C%20sed%20diam%20nonummy%20nibh%20euismod%20tincidunt%20ut%20laoreet%20dolore%20magn';
        $this->longText .= 'a%20aliquam%20erat%20volutpat.%20Ut%20wisi%20enim%20ad%20minim%20veniam%2C%20quis%20nostru';
        $this->longText .= 'd%20exerci%20tation%20ullamcorper%20suscipit%20lobortis%20nisl%20ut%20aliquip%20ex%20ea%20';
        $this->longText .= 'commodo%20consequat.%20Duis%20autem%20vel%20eum%20iriure%20dolor%20in%20hendrerit%20in%20v';
        $this->longText .= 'ulputate%20velit%20esse%20molestie%20consequat%2C%20vel%20illum%20dolore%20eu%20feugiat%20';
        $this->longText .= 'nulla%20facilisis.%20At%20vero%20eos%20et%20accusam%20et%20justo%20duo%20dolores%20et%20ea';
        $this->longText .= '%20rebum.%20Stet%20clita%20kasd%20gubergren%2C%20no%20sea%20takimata%20sanctus%20est%20Lor';
        $this->longText .= 'em%20ipsum%20dolor%20sit%20amet.%20Lorem%20ipsum%20dolor%20sit%20amet%2C%20consetetur%20sa';
        $this->longText .= 'dipscing%20elitr%2C%20sed%20diam%20nonumy%20eirmod%20tempor%20invidunt%20ut%20labore%20et%';
        $this->longText .= '20dolore%20magna%20aliquyam%20erat%2C%20sed%20diam%20voluptua.%20At%20vero%20eos%20et%20ac';
        $this->longText .= 'cusam%20et%20justo%20duo%20dolores%20et%20ea%20rebum.%20Stet%20clita%20kasd%20gubergren%2C';
        $this->longText .= '%20no%20sea%20takimata%20sanctus%20est%20Lorem%20ipsum%20dolor%20sit%20amet.%20Lorem%20ips';
        $this->longText .= 'um%20dolor%20sit%20amet%2C%20consetetur%20sadipscing%20elitr%2C%20At%20accusam%20aliquyam%';
        $this->longText .= '20diam%20diam%20dolore%20dolores%20duo%20eirmod%20eos%20erat%2C%20et%20nonumy%20sed%20temp';
        $this->longText .= 'or%20et%20et%20invidunt%20justo%20labore%20Stet%20clita%20ea%20et%20gubergren%2C%20kasd%20';
        $this->longText .= 'magna%20no%20rebum.%20sanctus%20sea%20sed%20takimata%20ut%20vero%20voluptua.%20est%20Lorem';
        $this->longText .= '%20ipsum%20dolor%20sit%20amet.%20Lorem%20ipsum%20dolor%20sit%20ame.';

        for ($i = 0; $i < 11 * 1000; $i++) {
            $this->contentMaxBatchSize_[] = 'wt?p=300,0';
        }

        for ($i = 0; $i < 9 * 1000; $i++) {
            $this->maxPayloadSize_[] = 'wt?p=300,0&cp1=' . $this->longText;
        }
    }

    public function testNewConsumerCurl()
    {
        $c = new MappIntelligenceConfig(array(
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));
        new MappIntelligenceConsumerCurl($c->build());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertEquals('', $fileContent[0]);
    }

    public function testBatchRequestResponding200()
    {
        $c = new MappIntelligenceConfig(array(
            'trackId' => '123451234512345',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));
        $consumer = new MappIntelligenceConsumerCurl($c->build());

        $this->assertEquals(true, $consumer->sendBatch(array(
            'wt?p=300,0'
        )));

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended(
            'Send batch data to https://analytics01.wt-eu02.net/123451234512345/batch (1 req.)',
            $fileContent[0]
        );
        $this->assertContainsExtended('Batch request responding the status code 200', $fileContent[1]);
    }

    public function testBatchRequestResponding404()
    {
        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));
        $consumer = new MappIntelligenceConsumerCurl($c->build());

        $this->assertEquals(false, $consumer->sendBatch(array(
            'wt?p=300,0'
        )));

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended(
            'Send batch data to https://analytics01.wt-eu02.net/111111111111111/batch (1 req.)',
            $fileContent[0]
        );
        $this->assertContainsExtended('Batch request responding the status code 404', $fileContent[1]);
        $this->assertContainsExtended('[0]:', $fileContent[2]);
    }

    public function testMaxBatchSize()
    {
        $c = new MappIntelligenceConfig(array(
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));
        $consumer = new MappIntelligenceConsumerCurl($c->build());

        $this->assertEquals(false, $consumer->sendBatch($this->contentMaxBatchSize_));

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Batch size is larger than 10000 req. (11000 req.)', $fileContent[0]);
    }

    public function testMaxPayloadSize()
    {
        $c = new MappIntelligenceConfig(array(
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));
        $consumer = new MappIntelligenceConsumerCurl($c->build());

        $this->assertEquals(false, $consumer->sendBatch($this->maxPayloadSize_));

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Payload size is larger than 24MB (34.7MB)', $fileContent[0]);
    }
}
