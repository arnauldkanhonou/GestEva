import { computed } from "vue";

export default function useSubmitButtonForm(form, Errors) {
    const isFormButtonDisabled = computed(() => {
        let disabled = true;
        for (let prop in form) {
            console.log(prop);
            if (prop !== 'email') {
                if (form[prop] ==='' || Errors[prop]) {
                    disabled = true;
                    break;
                }
            }

            disabled = false;
        }
        return disabled;
    });

    return {isFormButtonDisabled}
}
