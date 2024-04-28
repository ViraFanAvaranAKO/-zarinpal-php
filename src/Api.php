<?php

namespace Ako\Zarinpal\Php;

use Ako\Zarinpal\Php\Abstracts\Core;
use Ako\Zarinpal\Php\Contracts\IZarinpalQueryable;
use Ako\Zarinpal\Php\Contracts\IZarinpalTransaction;
use Ako\Zarinpal\Php\Contracts\StrategiesEnum;
use Ako\Zarinpal\Php\Drivers\QueryBuilderDriver;
use Ako\Zarinpal\Php\Helpers\Utils;
use Ako\Zarinpal\Php\Models\AccessToken;
use Ako\Zarinpal\Php\Models\Address;
use Ako\Zarinpal\Php\Models\Application;
use Ako\Zarinpal\Php\Models\BankAccount;
use Ako\Zarinpal\Php\Models\Coupon;
use Ako\Zarinpal\Php\Models\DayTransactionAvg;
use Ako\Zarinpal\Php\Models\Faq;
use Ako\Zarinpal\Php\Models\FeeGroup;
use Ako\Zarinpal\Php\Models\ForeignNational;
use Ako\Zarinpal\Php\Models\IBAN;
use Ako\Zarinpal\Php\Models\IncomeChart;
use Ako\Zarinpal\Php\Models\InstantPayout;
use Ako\Zarinpal\Php\Models\InstantPayoutChart;
use Ako\Zarinpal\Php\Models\Investment;
use Ako\Zarinpal\Php\Models\Invoice;
use Ako\Zarinpal\Php\Models\Note;
use Ako\Zarinpal\Php\Models\PackagePayment;
use Ako\Zarinpal\Php\Models\Payout;
use Ako\Zarinpal\Php\Models\PayoutChart;
use Ako\Zarinpal\Php\Models\ProductForm;
use Ako\Zarinpal\Php\Models\Reconciliation;
use Ako\Zarinpal\Php\Models\ReconciliationChart;
use Ako\Zarinpal\Php\Models\Referrer;
use Ako\Zarinpal\Php\Models\ReferrerInvoice;
use Ako\Zarinpal\Php\Models\RefundChart;
use Ako\Zarinpal\Php\Models\RefundFeeInquiry;
use Ako\Zarinpal\Php\Models\RevenueChart;
use Ako\Zarinpal\Php\Models\RevenueMethodChart;
use Ako\Zarinpal\Php\Models\Session;
use Ako\Zarinpal\Php\Models\SessionExport;
use Ako\Zarinpal\Php\Models\SuggestAnswer;
use Ako\Zarinpal\Php\Models\Terminal;
use Ako\Zarinpal\Php\Models\TerminalCategory;
use Ako\Zarinpal\Php\Models\TerminalCustomer;
use Ako\Zarinpal\Php\Models\TerminalUser;
use Ako\Zarinpal\Php\Models\Ticket;
use Ako\Zarinpal\Php\Models\TicketDepartment;
use Ako\Zarinpal\Php\Models\TicketReply;
use Ako\Zarinpal\Php\Models\User;
use Ako\Zarinpal\Php\Models\UserAnnouncement;
use Ako\Zarinpal\Php\Models\UserTaxRegister;
use Ako\Zarinpal\Php\Models\ZarinCard;
use Ako\Zarinpal\Php\Models\ZarinLink;

class Api extends Core
{
    public function transaction(?StrategiesEnum $via = null, array $data = []): IZarinpalTransaction
    {
        $via ??= Utils::as_enum_or_null(StrategiesEnum::class, $this->settings->defaults->strategy);
        return $via->instantiate_driver($this, $data);
    }

    public function accessTokens(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, AccessToken::class);
    }
    public function addresses(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Address::class);
    }

    public function applications(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Application::class);
    }

    public function bankAccounts(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, BankAccount::class);
    }

    public function coupons(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Coupon::class);
    }

    public function averageDayTransactions(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, DayTransactionAvg::class);
    }

    public function faqs(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Faq::class);
    }

    public function feeGroups(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, FeeGroup::class);
    }

    public function foreignNationalities(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, ForeignNational::class);
    }

    public function iban(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, IBAN::class);
    }

    public function incomeChart(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, IncomeChart::class);
    }

    public function instantPayouts(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, InstantPayout::class);
    }

    public function instantPayoutChart(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, InstantPayoutChart::class);
    }

    public function investments(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Investment::class);
    }

    public function invoices(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Invoice::class);
    }

    public function notes(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Note::class);
    }

    public function packagePayments(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, PackagePayment::class);
    }

    public function payouts(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Payout::class);
    }

    public function payoutChart(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, PayoutChart::class);
    }

    public function productForms(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, ProductForm::class);
    }

    public function reconciliations(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Reconciliation::class);
    }

    public function reconciliationChart(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, ReconciliationChart::class);
    }

    public function referrers(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Referrer::class);
    }

    public function referrerInvoices(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, ReferrerInvoice::class);
    }

    public function refundChart(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, RefundChart::class);
    }

    public function refundFees(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, RefundFeeInquiry::class);
    }

    public function revenueChart(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, RevenueChart::class);
    }

    public function revenueMethodChart(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, RevenueMethodChart::class);
    }

    public function sessions(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Session::class);
    }

    public function sessionExports(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, SessionExport::class);
    }

    public function answerSuggestions(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, SuggestAnswer::class);
    }

    public function terminals(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Terminal::class);
    }

    public function terminalCategories(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, TerminalCategory::class);
    }

    public function terminalCustomers(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, TerminalCustomer::class);
    }

    public function terminalUsers(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, TerminalUser::class);
    }

    public function tickets(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, Ticket::class);
    }

    public function ticketDepartments(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, TicketDepartment::class);
    }

    public function ticketReplies(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, TicketReply::class);
    }

    public function users(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, User::class);
    }

    public function userAnnouncements(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, UserAnnouncement::class);
    }

    public function userTaxRegistries(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, UserTaxRegister::class);
    }

    public function zarinCards(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, ZarinCard::class);
    }

    public function zarinLinks(): IZarinpalQueryable
    {
        return new QueryBuilderDriver($this, ZarinLink::class);
    }
}
