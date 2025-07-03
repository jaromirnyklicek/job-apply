import js from '@eslint/js'
import vue from 'eslint-plugin-vue'
import vueParser from 'vue-eslint-parser'
import babelParser from '@babel/eslint-parser'

export default [
    js.configs.recommended,
    {
        files: ['**/*.vue'],
        languageOptions: {
            parser: vueParser,
            parserOptions: {
                parser: babelParser,
                ecmaVersion: 2022,
                sourceType: 'module',
                requireConfigFile: false,
            },
            globals: {
                fetch: 'readonly',
                console: 'readonly'
            },
        },
        plugins: {
            vue,
        },
        rules: {
            'vue/multi-word-component-names': 'off',
            'no-console': ['warn', { allow: ['error'] }],
            'no-debugger': 'warn',
        },
    }
]
