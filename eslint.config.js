import js from "@eslint/js";
import globals from "globals";
import pluginVue from "eslint-plugin-vue";
import { defineConfig } from "eslint/config";

export default defineConfig([
  { 
    files: ["**/*.{js,mjs,cjs,vue}"], 
    plugins: { vue: pluginVue }, 
    extends: [
      js.configs.recommended,
      pluginVue.configs["flat/recommended"],
    ], 
    languageOptions: { 
      globals: globals.browser 
    },
    rules: {
      "vue/multi-word-component-names": "off"
    }
  },
  pluginVue.configs["flat/essential"],
]);
