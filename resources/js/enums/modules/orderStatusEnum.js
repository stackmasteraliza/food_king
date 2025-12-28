const orderStatusEnum = Object.freeze({
    PENDING: 1,
    ACCEPT: 4,
    PREPARING: 7,
    PREPARED: 8,
    OUT_FOR_DELIVERY: 10,
    DELIVERED: 13,
    CANCELED: 16,
    REJECTED: 19,
    RETURNED: 22,
});
export default orderStatusEnum;
