export const mixin = {
    globalFunctions: {
        /**
         * 
         * @param {string, lower camel case} component 
         * @param {string} field 
         * @param {string} camp, if not specified: Applicant
         */
        getFieldData: async function (component, field, camp = null) {
            await axios
                .post("/api/getFieldData", {
                    id: window.applicant_id,
                    field: field,
                    camp: camp,
                })
                .then((response) => {
                    component[this.snake(field)] = response.data;
                });
            return component[this.snake(field)];
        },
        snake: (str) => {
            return (
                str &&
                str
                    .match(
                        /[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g
                    )
                    .map((x) => x.toLowerCase())
                    .join("_")
            );
        },
    },
};
