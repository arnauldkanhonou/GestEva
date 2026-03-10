
export default function useValidators() {
    const isEmpty = (fieldName, fieldValue) => {
        return fieldValue==='' ? "Le champ " + fieldName + " est obligatoire" : "";
    }
    const minLength = (fieldName, fieldValue, min) => {
        return fieldValue.length < min ? `Le champ ${fieldName} doit avoir au minimum ${min} caractères` : "";
    }

    const maxLength = (fieldName,fieldValue,max) => {
        return fieldValue.length > max ?'Le champ '+fieldName+' doit avoir au maximum '+ max + ' caractères' : "";
    }

    const isEmail = (fieldName, fieldValue) => {
        let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return !re.test(fieldValue) ? "Veuillez saisir une adresse " + fieldName + " valide" : "";
    }
    return {
        isEmpty,
        minLength,
        maxLength,
        isEmail
    }
}
