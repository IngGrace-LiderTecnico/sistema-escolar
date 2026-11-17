<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class LimitesModularesTest extends TestCase
{
    public function test_modulos_no_importan_otros_modulos_ni_modelos_del_nucleo(): void
    {
        $base = dirname(__DIR__, 2).'/app/Modules';
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base)) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $text = file_get_contents($file->getPathname());
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($base) + 1));
            $own = explode('/', $relative)[0];
            preg_match_all('/App\\\\Modules\\\\([A-Za-z]+)\\\\/', $text, $matches);
            foreach ($matches[1] as $referenced) {
                $this->assertSame($own, $referenced, $relative.' importa otro módulo');
            }
            $this->assertStringNotContainsString('App\\Core\\', $text, $relative.' debe usar contratos');
        }
    }
}
