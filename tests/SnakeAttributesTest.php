<?php

namespace Halpdesk\LaravelTraits\Tests;

use Halpdesk\LaravelTraits\Tests\Models\Company;
use Halpdesk\LaravelTraits\Tests\Models\Order;
use Carbon\Carbon;

class SnakeAttributesTest extends TestCase
{
    /**
     * @covers Halpdesk\LaravelTraits\Traits\SnakeAttributes::getAttribute()
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
     * @covers Halpdesk\LaravelTraits\Traits\SnakeAttributes::setAttribute()
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
     * @covers Halpdesk\LaravelTraits\Traits\SnakeAttributes::fill()
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
     * @covers Halpdesk\LaravelTraits\Traits\SnakeAttributes::fill()
     */
    public function testDateFormat()
    {
        $now = Carbon::now();
        $attributes = [
            'companyName'  => 'My New Company',
            'email'        => 'hello@example.com',
            'registeredAt' => $now
        ];
        $company = (new Company)->fill($attributes);
        $company->save();

        $this->assertEquals($now->format('Y-m-d'), $company->registeredAt);
    }
}
