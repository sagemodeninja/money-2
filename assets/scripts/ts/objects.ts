class Account {
    Id: bigint;
    Shortcode: string;
    Title: string;
    CategoryId: bigint;
    Category: string;
    CategoryColor: string;
    CategoryOrder: number;
    AccountNumber: string;
    BankIcon: string;
    Status: AccountStatus
}

class Transaction {
    Id: bigint;
    Date: string;
    Description: string;
    Debit: number;
    Credit: number;
    Posted: boolean
}