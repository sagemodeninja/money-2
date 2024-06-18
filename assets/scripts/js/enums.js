var Operation;
(function (Operation) {
    Operation[Operation["Create"] = 0] = "Create";
    Operation[Operation["Update"] = 1] = "Update";
})(Operation || (Operation = {}));
var AccountStatus;
(function (AccountStatus) {
    AccountStatus[AccountStatus["Active"] = 1] = "Active";
    AccountStatus[AccountStatus["Closed"] = 0] = "Closed";
})(AccountStatus || (AccountStatus = {}));
var TransactionType;
(function (TransactionType) {
    TransactionType[TransactionType["Deposit"] = 0] = "Deposit";
    TransactionType[TransactionType["Withdraw"] = 1] = "Withdraw";
    TransactionType[TransactionType["Transfer"] = 2] = "Transfer";
})(TransactionType || (TransactionType = {}));
