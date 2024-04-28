<?php

namespace Ako\Zarinpal\Php\Contracts;

interface IZarinpal
{
    public function transaction(?StrategiesEnum $via = null, array $data = []): IZarinpalTransaction;
    public function getSettings(): object;


    public function accessTokens(): IZarinpalQueryable;
    public function addresses(): IZarinpalQueryable;
    public function applications(): IZarinpalQueryable;
    public function bankAccounts(): IZarinpalQueryable;
    public function coupons(): IZarinpalQueryable;
    public function averageDayTransactions(): IZarinpalQueryable;
    public function faqs(): IZarinpalQueryable;
    public function feeGroups(): IZarinpalQueryable;
    public function foreignNationalities(): IZarinpalQueryable;
    public function iban(): IZarinpalQueryable;
    public function incomeChart(): IZarinpalQueryable;
    public function instantPayouts(): IZarinpalQueryable;
    public function instantPayoutChart(): IZarinpalQueryable;
    public function investments(): IZarinpalQueryable;
    public function invoices(): IZarinpalQueryable;
    public function notes(): IZarinpalQueryable;
    public function packagePayments(): IZarinpalQueryable;
    public function payouts(): IZarinpalQueryable;
    public function payoutChart(): IZarinpalQueryable;
    public function productForms(): IZarinpalQueryable;
    public function reconciliations(): IZarinpalQueryable;
    public function reconciliationChart(): IZarinpalQueryable;
    public function referrers(): IZarinpalQueryable;
    public function referrerInvoices(): IZarinpalQueryable;
    public function refundChart(): IZarinpalQueryable;
    public function refundFees(): IZarinpalQueryable;
    public function revenueChart(): IZarinpalQueryable;
    public function revenueMethodChart(): IZarinpalQueryable;
    public function sessions(): IZarinpalQueryable;
    public function sessionExports(): IZarinpalQueryable;
    public function answerSuggestions(): IZarinpalQueryable;
    public function terminals(): IZarinpalQueryable;
    public function terminalCategories(): IZarinpalQueryable;
    public function terminalCustomers(): IZarinpalQueryable;
    public function terminalUsers(): IZarinpalQueryable;
    public function tickets(): IZarinpalQueryable;
    public function ticketDepartments(): IZarinpalQueryable;
    public function ticketReplies(): IZarinpalQueryable;
    public function users(): IZarinpalQueryable;
    public function userAnnouncements(): IZarinpalQueryable;
    public function userTaxRegistries(): IZarinpalQueryable;
    public function zarinCards(): IZarinpalQueryable;
    public function zarinLinks(): IZarinpalQueryable;
}
