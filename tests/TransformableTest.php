<?php

namespace Halpdesk\LaravelTraits\Tests;

use Halpdesk\LaravelTraits\Tests\Models\Company;
use Halpdesk\LaravelTraits\Tests\Models\Order;
use Halpdesk\LaravelTraits\Tests\Transformers\CompanyTransformer;
use Halpdesk\LaravelTraits\Tests\Transformers\OrderTransformer;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Carbon\Carbon;

class TransformableTest extends TestCase
{
    /**
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::getTransformer()
     * @group Transformable
     */
    public function testGetTransformer()
    {
        $company = new Company();
        $transformer = $company->getTransformer();
        $expected = CompanyTransformer::class;
        $this->assertEquals($transformer, $expected);
    }

    /**
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::transform()
     * @group Transformable
     */
    public function testTransformModel()
    {
        $company = new Company([
            "company_name"  => "New company",
            "email"         => "hello@example.com",
            "registered_at" => Carbon::now(),
        ]);
        $transformed = $company->transform();
        $expected = (new CompanyTransformer)->transform($company);
        $this->assertEquals($transformed, $expected);
    }

    /**
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::getIncludes()
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::load()
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::getRelationTableNames()
     * @group Transformable
     */
    public function testRelationLoadedAndGetIncludes()
    {
        $company = Company::create([
            "company_name"  => "New company",
            "email"         => "hello@example.com",
            "registered_at" => Carbon::now(),
        ]);
        $order = Order::create([
            "company_id"    => 1,
            "order_number"  => "500100",
        ]);
        $order->load(['company']);
        $company->load(['orders']);

        $this->assertEquals(['orders'], $company->getIncludes());
        $this->assertEquals(['products', 'company'], $order->getIncludes());

        $this->assertEquals(['orders'], $company->getRelationTableNames());
        $this->assertEquals(['products', 'companies'], $order->getRelationTableNames());

        $this->assertTrue($company->relationLoaded('orders'));
        $this->assertTrue($order->relationLoaded('company'));
        $this->assertFalse($company->relationLoaded('notLoaded'));
    }

    /**
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::load()
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::transform()
     * @group Transformable
     */
    public function testLoadRelatedModelOnModel()
    {
        $company = Company::create([
            "company_name"  => "New company",
            "email"         => "hello@example.com",
            "registered_at" => Carbon::now(),
        ]);
        $order = Order::create([
            "company_id"    => 1,
            "order_number"  => "500100",
        ]);
        $company->load(['orders']);
        $order->load('company');

        $expectedCompany = (new CompanyTransformer)->transform($company);
        $expectedOrder = (new OrderTransformer)->transform($order);
        $expectedCompany['orders']  = [$expectedOrder];
        $expectedOrder['company']   = $expectedCompany;
        unset($expectedOrder['company']['orders']);

        $this->assertEquals($expectedCompany, $company->transform());
        $this->assertEquals($expectedOrder, $order->transform());
    }

    /**
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::load()
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::transform()
     * @group Transformable
     */
    public function testSelectWithRelatedModelOnModel()
    {
        Company::create([
            "company_name"  => "New company",
            "email"         => "hello@example.com",
            "registered_at" => Carbon::now(),
        ]);
        $orderOne = Order::create([
            "company_id"    => 1,
            "order_number"  => "500100",
        ]);
        $orderTwo = Order::create([
            "company_id"    => 1,
            "order_number"  => "500200",
        ]);
        $company = Company::with(['orders'])->findOrFail(1);

        $expectedCompany = (new CompanyTransformer)->transform($company);
        $expectedOrderOne = (new OrderTransformer)->transform($orderOne);
        $expectedOrderTwo = (new OrderTransformer)->transform($orderTwo);
        $expectedCompany['orders']  = [$expectedOrderOne, $expectedOrderTwo];

        $this->assertEquals($expectedCompany, $company->transform());
    }


    /**
     * @covers Halpdesk\LaravelTraits\Traits\Transformable::load()
     * @group Transformable
     */
    public function testLoadRelationThatIsNeitherModelOrCollection()
    {
        $company = Company::create([
            "company_name"  => "New company",
            "email"         => "hello@example.com",
            "registered_at" => Carbon::now(),
        ]);
        $exceptionThrown = false;
        try {

            $company->load(['doesNotWorkRelation']);
        } catch (RelationNotFoundException $e) {
            $this->assertEquals($e->getMessage(), 'Call to undefined relationship [doesNotWorkRelation] on model [Halpdesk\LaravelTraits\Tests\Models\Company].');
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }
}
