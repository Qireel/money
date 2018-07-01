# money
An extendable library calculating the measure rate of the specified currency from different sources

# installation

> composer require qireel/money

# usage 

> use Qireel\Money\Money;
> $money = new Money;
> $currencyRate = $money->getMeasureRate('USD', 'RUR', new DateTime));
>
> echo $currencyRate->rate;

