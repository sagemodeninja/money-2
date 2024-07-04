<?php
namespace Api\Controllers;

use Framework\Api\Controller\ControllerBase;
use Framework\Api\Attributes\{Get};

class MainController extends ControllerBase
{
    #[Get("getUsers")]
    public function getUsers(string $pDisplayType, string $pPrimaryKey, string $pSearchString)
    {
        $users = [];

        for ($i = 1; $i <= 18; $i++) {
            $users[] = new User($i);
        }

        return $this->Ok($users);
    }

    #[Get("getMeterReaders")]
    public function getMeterReaders(string $pDisplayType, string $pPrimaryKey, string $pSearchString)
    {
        $readers = [];

        for ($i = 1; $i <= 18; $i++) {
            $reader = new MeterReader();
    
            $reader->id = 60 + $i;
            $reader->no = 420 + $i;
            $reader->name = "M-CHAN-$i";
            $reader->remarks = '';

            $readers[] = $reader;
        }

        return $this->Ok($readers);
    }

    #[Get("getMeterReadingPeriodIfExisting")]
    public function getMeterReadingPeriodIfExisting(int $pYear, int $pMonth, int $pMeterReaderId)
    {
        $period = new Period();

        $period->Id = $pMeterReaderId + 10;
        $period->Year = $pYear;
        $period->Month = $pMonth;
        $period->TotalConsumer = 20;
        $period->PeriodStatus = ($pMeterReaderId % 2) == 0
            ? 'Close'
            : 'Open';

        return $this->Ok([$period]);
    }

    #[Get("getWaterConnectionLocationByMeterReader")]
    public function getWaterConnectionLocationByMeterReader(int $pMeterReaderId)
    {
        $locations = [];

        for ($i = 1; $i < 20; $i++) {
            $locations[] = new MeterLocation($i, "Location $i");
        }

        return $this->Ok($locations);
    }

    #[Get("getMeterReadingsByLocation")]
    public function getMeterReadingsByLocation(int $pMeterReadingPeriodId, int $pWaterConnectionLocationId)
    {
        $readings = [];

        for ($i = 1; $i < 20; $i++) {
            $readings[] = new MeterReading($i);
        }

        return $this->Ok($readings);
    }
}

class User
{
    public int $Id;
    public string $Username;
    public string $EmployeeName;
    public string $EmployeeDesignation;
    public string $Password;

    public function __construct(int $index)
    {
        $designations = ["Admin", "Aaccountant", "Treasury"];

        $this->Id = $index;
        $this->Username = "user_$index";
        $this->EmployeeName = "User $index";
        $this->EmployeeDesignation = $designations[$index % 3];
        $this->Password = md5("passw0rd_$index");
    }
}

class MeterReader
{
    public int $id;
    public int $no;
    public string $name;
    public string $remarks;
}

class Period
{
    public int $Id;
    public int $Year;
    public int $Month;
    public int $TotalConsumer;
    public string $PeriodStatus;
}

class MeterLocation
{
    public int $Id;
    public string $Location;

    public function __construct($id, $location)
    {
        $this->Id = $id;
        $this->Location = $location;
    }
}

class MeterReading
{
    public int $Id;
    public string $MeterNo;
    public string $ConsumerName;
    public int $PreviousReading;
    public float $PricePerCuM;

    public function __construct(int $index)
    {
        $this->Id = $index;
        $this->MeterNo = "MN-$index";
        $this->ConsumerName = "Consumer $index";
        $this->PreviousReading = rand(25, 350);
        $this->PricePerCuM = 5.2;
    }
}
?>