import {ref} from "vue";
import {reactive} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";

export default function useEvaluation() {
    const errors = ref('');
    const tabError = ref('');
}