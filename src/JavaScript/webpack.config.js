const path = require('path');

module.exports = {
    target: 'node',
    mode: 'production',
    entry: './rosetta.js',
    output: {
        filename: 'rosetta.js',
        path: path.resolve(__dirname, 'dist'),
    },
};
