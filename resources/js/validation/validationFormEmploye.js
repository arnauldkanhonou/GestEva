import useFormValidation from "./useFormValidation";

export default function useValidateFormEmploye(form) {
    const {Errors, validateEmailField,validateNameField, validateLength} = useFormValidation();

    const validateInputMatricule = () => {
        (form.matricule === '' ? validateNameField('matricule', form.matricule) :
            validateLength('matricule', form.matricule, 6))
    }
    const validateInputNom = () => {
        validateNameField('nom', form.nom)
    };

    const validateInputPrenoms = () => {
        validateNameField('prenoms', form.prenoms)
    }

    const validateInputsexe = () => {
        validateNameField('sexe', form.sexe)
    }
    const validateInputemail = () => {
        validateEmailField('email', form.email)
    }
    const validateInputTelephone = () => {
        validateNameField('telephone', form.telephone)
    }
    const validateInputdateEmbauche = () => {
        validateNameField('dateEmbauche', form.dateEmbauche)
    }
    const validateInputfonction = () => {
        validateNameField('fonction', form.fonction)
    }
    const validateInputunite = () => {
        validateNameField('unite', form.unite)
    }

    return {
        Errors,
        validateInputMatricule,
        validateInputNom,
        validateInputPrenoms,
        validateInputemail,
        validateInputsexe,
        validateInputdateEmbauche,
        validateInputfonction,
        validateInputunite,
        validateInputTelephone
    }

}
