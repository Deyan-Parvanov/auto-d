import { isRef } from 'vue';

export const useMonthlyPayment = (total, interestRate, duration) => {
    const principle = isRef(total) ? total.value : total;
    const monthlyInterest = (isRef(interestRate) ? interestRate.value : interestRate) / 100 / 12;
    const numberOfPaymentMonths = (isRef(duration) ? duration.value : duration) * 12;

    const monthlyPayment = principle * monthlyInterest * (Math.pow(1 + monthlyInterest, numberOfPaymentMonths)) / (Math.pow(1 + monthlyInterest, numberOfPaymentMonths) - 1);
    const totalPaid = (isRef(duration) ? duration.value : duration) * 12 * monthlyPayment;
    const totalInterest = totalPaid - (isRef(total) ? total.value : total);

    return { monthlyPayment, totalPaid, totalInterest }
}
