const path = require('path');

module.exports = {
  entry: {
    datatableStandard: './assets/js/datatables/datatable-standard.js',
    datatableExport: './assets/js/datatables/datatable-export.js',
    datatableDropdown: './assets/js/datatables/datatable-dropdown.js',
    globalScript: './assets/js/global-script.js',              // File global
    contentAdmin: './assets/js/content-admin.js',              // File admin
    contentHeadoffice: './assets/js/content-headoffice.js',
  },
  output: {
    filename: '[name].bundle.js',     // Output file per entry point
    path: path.resolve(__dirname, 'assets/public/js'), // Direktori output
  },
  mode: 'development',
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: ['style-loader', 'css-loader'],
      },
    ]
  },
  resolve: {
    alias: {
      jquery: path.resolve(__dirname, 'node_modules/jquery/dist/jquery.min.js')
    }
  }
};
