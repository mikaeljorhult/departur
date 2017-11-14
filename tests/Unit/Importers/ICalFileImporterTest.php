<?php

namespace Tests\Unit\Importers;

use Departur\Importers\ICalFileImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ICalFileImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Importer can be instantiated.
     *
     * @return void
     */
    public function testICalFileImporterCanBeInstantiated()
    {
        $importer = new ICalFileImporter();
        $this->assertTrue(method_exists($importer, 'get'));
    }
}
