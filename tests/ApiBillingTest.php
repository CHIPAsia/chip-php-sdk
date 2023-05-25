<?php declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use Chip\Model\Billing as ModelBilling;
use Chip\Model\BillingTemplate as ModelBillingTemplate;
use Chip\Model\BillingTemplateClient as ModelBillingTemplateClient;
use Chip\Model\Purchase as ModelPurchase;

require_once "ApiTest.php";

final class ApiBillingTest extends ApiTest
{
	public function testCreateBilling() {
		$container = [];
		$history = Middleware::history($container);
		$model = new ModelBilling();
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->createBilling($model);
		$transaction = $container[0];
		
		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString('billing/', $transaction['request']->getUri()->getPath());
	}

	public function testCreateBillingTemplate() {
		$container = [];
		$history = Middleware::history($container);
		$model = new ModelBillingTemplate();
		$api = $this->getMockApi(new MockHandler([
			new Response(200, [], '{}')
		]), $history);
		$api->createBillingTemplate($model);
		$transaction = $container[0];

		$this->assertEquals('POST', $transaction['request']->getMethod());
		$this->assertStringContainsString('billing_templates/', $transaction['request']->getUri()->getPath());
	}
}