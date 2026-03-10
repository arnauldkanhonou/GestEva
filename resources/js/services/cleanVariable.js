export default function useUpdateVariable(Errors,form) {
    const cleanVar = ()=>{
        for (let prop in form) {
            Errors[prop]='';
        }
    }

    return {cleanVar};
}