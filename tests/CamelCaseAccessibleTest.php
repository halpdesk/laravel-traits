<?php

namespace Halpdesk\LaravelTraits\Tests;

use Halpdesk\LaravelTraits\Tests\Models\Company;
use Carbon\Carbon;

class CamelCaseAccessibleTest extends TestCase
{
    /**
     * @group CamelCaseAccessible
     * @covers Halpdesk\LaravelTraits\Traits\CamelCaseAccessibleTest::getAttribute()
     */
    public function testGetAttribute()
    {
        $company = factory(Company::class)->create([
            'company_name'  => 'My New Company',
            'email'         => 'hello@example.com',
        ]);
        $this->assertEquals('My New Company', $company->companyName);
        $this->assertEquals('hello@example.com', $company->email);
    }

    /**
     * @group CamelCaseAccessible
     * @covers Halpdesk\LaravelTraits\Traits\CamelCaseAccessibleTest::getAttribute()
     */
    public function testGetTimestamps()
    {
        $company = factory(Company::class)->create([
            'company_name'  => 'My New Company',
            'email'         => 'hello@example.com',
        ]);
        $this->assertEquals($company->createdAt, $company->created_at);
        $this->assertEquals($company->updatedAt, $company->updated_at);
        $this->assertEquals($company->deletedAt, $company->deleted_at);
    }

    /**
     * @group CamelCaseAccessible
     * @covers Halpdesk\LaravelTraits\Traits\CamelCaseAccessibleTest::setAttribute()
     */
    public function testSetAttribute()
    {
        $company = factory(Company::class)->create([
            'company_name'  => 'My New Company',
            'email'         => 'hello@example.com',
        ]);
        $company->companyName = 'Updated Company Name';
        $company->save();

        $this->assertDatabaseHas('companies', [
            'company_name' => 'Updated Company Name'
        ]);
    }

    /**
     * @group CamelCaseAccessible
     * @covers Halpdesk\LaravelTraits\Traits\CamelCaseAccessibleTest::fill()
     */
    public function testFill()
    {
        $attributes = [
            'companyName'  => 'My New Company',
            'email'        => 'hello@example.com',
            'registeredAt' => Carbon::now()->format('Y-m-d H:i:s')
        ];
        $company = (new Company)->fill($attributes);
        $company->save();

        $this->assertDatabaseHas('companies', array_keys_to_snake_case($attributes));
    }

    /**
     * @group CamelCaseAccessible
     * @covers Halpdesk\LaravelTraits\Traits\CamelCaseAccessibleTest::fill()
     */
    public function testDateFormat()
    {
        $this->markTestSkipped();
        $now = Carbon::now();
        $attributes = [
            'companyName'  => 'My New Company',
            'email'        => 'hello@example.com',
            'registeredAt' => $now
        ];
        $company = (new Company)->fill($attributes);
        $company->save();

        $this->assertEquals($now->format('Y-m-d'), (string)$company->registeredAt);
    }
}
