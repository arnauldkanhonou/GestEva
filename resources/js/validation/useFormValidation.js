import { reactive } from "vue";
const Errors = reactive({});
import useValidators from "./validator";

export default function useFormValidation() {
    const { isEmpty, maxLength, isEmail,minLength } = useValidators();
    const validateNameField = (fieldName, fieldValue) => {
        Errors[fieldName] = fieldValue==='' ? isEmpty(fieldName, fieldValue) : ''
    }

    const validateLength = (fieldName, fieldValue,value,isMax=true) => {
        if (isMax){
            Errors[fieldName] = maxLength(fieldName, fieldValue,value);
        }else {
            Errors[fieldName] = minLength(fieldName, fieldValue,value);
        }

    }
    const validateEmailField = (fieldName, fieldValue) => {
        Errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isEmail(fieldName, fieldValue)
    }

    return {
        Errors,
        validateLength,
        validateNameField,
        validateEmailField
    }
}
