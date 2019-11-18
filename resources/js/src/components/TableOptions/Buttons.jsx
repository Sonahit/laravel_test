import React from 'react';
import PropTypes from 'prop-types';

import DownloadHelper from '@helpers/DownloadHelper';

const downloadHelper = new DownloadHelper();

export default function Buttons(props) {
  const { external } = props;
  return (
    <section className="options__download">
      <div className="options__buttons">
        <button
          type="button"
          className="options__button options__download__pdf"
          onClick={() => downloadHelper.downloadPDF()}
        >
          Download PDF
        </button>
        <button
          type="button"
          className="options__button options__download__csv"
          onClick={({ target }) => downloadHelper.downloadCSV(target)}
        >
          Download CSV
        </button>
        {!external && (
          <button
            type="button"
            className="options__button options__download__csv"
            onClick={() => props.fetchAllData()}
          >
            Get all meals
          </button>
        )}
      </div>
      <div className="options__buttons">
        {!external && !localStorage.getItem('table') && (
          <button
            type="button"
            className="options__button options__download__csv"
            onClick={() => props.rememberTable()}
          >
            Remember Table
          </button>
        )}
        {!external && localStorage.getItem('table') && (
          <button
            type="button"
            className="options__button options__download__csv"
            onClick={() => props.forgetTable()}
          >
            Forget Table
          </button>
        )}
        {!external && (
          <button
            type="button"
            className="options__button options__download__csv"
            onClick={() => props.refreshTable()}
          >
            Refresh Table
          </button>
        )}
        <button
          type="button"
          className="options__button options__download__csv"
          onClick={() => downloadHelper.tableToCsv()}
        >
          Convert table to CSV
        </button>
        {external && (
          <button
            type="button"
            className="options__button options__download__csv"
            onClick={() => props.stopRenderImport()}
          >
            Delete import data
          </button>
        )}
      </div>
    </section>
  );
}

Buttons.propTypes = {
  fetchAllData: PropTypes.func.isRequired,
  rememberTable: PropTypes.func.isRequired,
  forgetTable: PropTypes.func.isRequired,
  refreshTable: PropTypes.func.isRequired,
  external: PropTypes.bool.isRequired,
  stopRenderImport: PropTypes.func.isRequired
};
