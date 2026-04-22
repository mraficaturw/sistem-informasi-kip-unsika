<?php

namespace Tests\Unit;

use App\Http\Controllers\DashboardController;
use Tests\TestCase;
use ReflectionMethod;

/**
 * White-box Testing: DashboardController — resolveIpkAlert()
 *
 * Menguji seluruh cabang logika (code path) pada method resolveIpkAlert().
 * Method ini private, sehingga diakses via ReflectionMethod.
 *
 * Code paths:
 *   Path 1: $latestKhs === null   → ['', 'success']
 *   Path 2: $ipk < 3.00           → ['Perlu ditingkatkan', 'danger']
 *   Path 3: $ipk >= 3.00 && < 3.50 → ['Lebih baik ditingkatkan', 'warning text-dark']
 *   Path 4: $ipk >= 3.50          → ['Good Job, Pertahankan!', 'success']
 */
class DashboardControllerTest extends TestCase
{
    private ReflectionMethod $method;
    private DashboardController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new DashboardController();
        $this->method = new ReflectionMethod(DashboardController::class, 'resolveIpkAlert');
        $this->method->setAccessible(true);
    }

    /**
     * WB-D-01: Jika belum ada KHS (null), alert kosong dan warna success.
     */
    public function test_resolve_ipk_alert_returns_empty_when_no_khs(): void
    {
        [$message, $color] = $this->method->invoke($this->controller, null);

        $this->assertEquals('', $message);
        $this->assertEquals('success', $color);
    }

    /**
     * WB-D-02: IPK < 3.00 → 'danger'.
     */
    public function test_resolve_ipk_alert_danger_when_ipk_below_3(): void
    {
        $khs = (object) ['ipk' => 2.85];
        [$message, $color] = $this->method->invoke($this->controller, $khs);

        $this->assertEquals('Perlu ditingkatkan', $message);
        $this->assertEquals('danger', $color);
    }

    /**
     * WB-D-03: IPK = 2.99 (boundary) → masih 'danger'.
     */
    public function test_resolve_ipk_alert_danger_at_boundary_2_99(): void
    {
        $khs = (object) ['ipk' => 2.99];
        [$message, $color] = $this->method->invoke($this->controller, $khs);

        $this->assertEquals('Perlu ditingkatkan', $message);
        $this->assertEquals('danger', $color);
    }

    /**
     * WB-D-04: IPK = 3.00 (boundary) → 'warning'.
     */
    public function test_resolve_ipk_alert_warning_at_boundary_3_00(): void
    {
        $khs = (object) ['ipk' => 3.00];
        [$message, $color] = $this->method->invoke($this->controller, $khs);

        $this->assertEquals('Lebih baik ditingkatkan', $message);
        $this->assertEquals('warning text-dark', $color);
    }

    /**
     * WB-D-05: IPK = 3.49 (boundary atas warning) → masih 'warning'.
     */
    public function test_resolve_ipk_alert_warning_at_boundary_3_49(): void
    {
        $khs = (object) ['ipk' => 3.49];
        [$message, $color] = $this->method->invoke($this->controller, $khs);

        $this->assertEquals('Lebih baik ditingkatkan', $message);
        $this->assertEquals('warning text-dark', $color);
    }

    /**
     * WB-D-06: IPK = 3.50 (boundary) → 'success'.
     */
    public function test_resolve_ipk_alert_success_at_boundary_3_50(): void
    {
        $khs = (object) ['ipk' => 3.50];
        [$message, $color] = $this->method->invoke($this->controller, $khs);

        $this->assertEquals('Good Job, Pertahankan!', $message);
        $this->assertEquals('success', $color);
    }

    /**
     * WB-D-07: IPK = 4.00 (nilai maksimum) → 'success'.
     */
    public function test_resolve_ipk_alert_success_at_max_4_00(): void
    {
        $khs = (object) ['ipk' => 4.00];
        [$message, $color] = $this->method->invoke($this->controller, $khs);

        $this->assertEquals('Good Job, Pertahankan!', $message);
        $this->assertEquals('success', $color);
    }

    /**
     * WB-D-08 (Regresi): resolveIpsAlert masih berfungsi dengan benar.
     */
    public function test_resolve_ips_alert_still_works(): void
    {
        $ipsMethod = new ReflectionMethod(DashboardController::class, 'resolveIpsAlert');
        $ipsMethod->setAccessible(true);

        // IPS tinggi → success
        $khs = (object) ['ips' => 3.75];
        [$message, $color] = $ipsMethod->invoke($this->controller, $khs);
        $this->assertEquals('Good Job, Pertahankan!', $message);
        $this->assertEquals('success', $color);

        // IPS rendah → danger
        $khs = (object) ['ips' => 2.50];
        [$message, $color] = $ipsMethod->invoke($this->controller, $khs);
        $this->assertEquals('Perlu ditingkatkan', $message);
        $this->assertEquals('danger', $color);
    }
}
