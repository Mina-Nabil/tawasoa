<?php

namespace Database\Seeders;

use App\Models\Variable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Variable::newVariable("Period of Data","Days");
        Variable::newVariable("Revenue","EGP");
        Variable::newVariable("Purchases","EGP");
        Variable::newVariable("Recivables","EGP");
        Variable::newVariable("Inventory","EGP");
        Variable::newVariable("Cogs until End of period","EGP");
        Variable::newVariable("Payables","EGP");
        Variable::newVariable("Tenor offered to client","Days");
        Variable::newVariable("Gross Profit","EGP");
        Variable::newVariable("Current Assests","EGP");
        Variable::newVariable("Current Liabilities","EGP");
        Variable::newVariable("Total Assests","EGP");
        Variable::newVariable("Total Liabilities","EGP");
        Variable::newVariable("Net Credit Sales ","EGP");
        Variable::newVariable("Avg. Accounts Recivables","EGP");
        Variable::newVariable("Total CR/TO","EGP");
  
    }
}
