module.exports = {
    rootDir: `${__dirname}/resources/tests`,
    moduleNameMapper: {
        "\\.(jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2|mp4|webm|wav|mp3|m4a|aac|oga)$": `${__dirname}/resources/tests/__mocks__/fileMock.js"`,
        "\\.(css|less|scss|sass)$": `${__dirname}/resources/tests/__mocks__/styleMock.js`,
        "^@src(.*)$": `${__dirname}/resources/js/src$1`,
        "^@helpers(.*)$": `${__dirname}/resources/js/src/helpers$1`
    },
    setupFiles: ["./setupJest.js"]
};
