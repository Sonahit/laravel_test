import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { withRouter } from 'react-router-dom';

import TableHelper from '@helpers/TableHelper';

import './ImportOptions.scss';

function useForceUpdate() {
  const [value, setValue] = useState(true); // boolean state
  return () => setValue(!value); // toggle the state to force render
}

function ImportOptions(props) {
  const forceUpdate = useForceUpdate();

  const handleSelectCSV = ev => {
    const select = new File([ev.target.files[0]], ev.target.files[0].name, {
      type: ev.target.files[0].type
    });
    if (window.FileReader && select) {
      const reader = new FileReader();
      reader.file_info = select;
      if (!select.name.match(/\w+(\.csv)/gi)) return;
      reader.onloadend = e => {
        if (e.target.readyState === FileReader.DONE) {
          const { result } = e.target;
          // eslint-disable-next-line no-unused-vars
          const [_, body] = TableHelper.prototype.csvAsTable(result);
          const tBody = body.map(row => ({
            id: parseInt(row[0]),
            date: row[1],
            class: row[2],
            type: row[3],
            plan_attributes: {
              codes: row[4].split(','),
              qty: parseInt(row[6]),
              price: parseInt(row[8])
            },
            fact_attributes: {
              codes: row[5].split(','),
              qty: parseInt(row[7]),
              price: parseInt(row[9])
            }
          }));
          const file = e.currentTarget.file_info;
          if (props.handleImportCSV(tBody)) {
            const text = `Файл ${file.name}, размером ${Math.round(
              file.size / 1024
            )} кбайт. Количество строк ${tBody.length}`;
            const span = document.querySelector('.import__data');
            span.innerText = text;
            document.querySelector('.import__reset').style.width = '100%';
            forceUpdate();
          }
        }
      };
      const blob = select.slice(0, select.size - 1);
      reader.readAsText(blob, 'utf-8');
      ev.currentTarget.value = '';
    }
  };

  const clearImport = () => {
    document.querySelector('.import__data').innerText = 'Choose file';
    document.querySelector('.import__reset').style.width = 'inherit';
    props.stopRenderImport();
    forceUpdate();
  };

  const inputHasText = (selector, text) => {
    const input = document.querySelector(selector);
    if (!input) return false;
    if (!input.innerText) return false;
    return !input.innerText.includes(text);
  };

  return (
    <section className="import">
      <div className="import__form">
        <fieldset>
          <legend>Import CSV to Table</legend>
          <section className="import__options">
            <div className="import__options__choose">
              <label htmlFor="input_csv" className="import__button">
                Choose File
                <input id="input_csv" type="file" className="hidden" onChange={handleSelectCSV} />
              </label>

              <span role="import_data" className="import__data">
                Choose file
              </span>
            </div>
            <div className="import__reset">
              <button
                type="button"
                role="import"
                className="import__button"
                disabled={!inputHasText('.import__data', 'Choose file')}
                onClick={() => props.history.push('/')}
              >
                Import Data
              </button>
              <button
                type="button"
                className="import__button import__options__delete"
                onClick={clearImport}
                disabled={!inputHasText('.import__data', 'Choose file')}
              >
                Delete Import CSV
              </button>
            </div>
          </section>
        </fieldset>
      </div>
    </section>
  );
}

export default withRouter(ImportOptions);

ImportOptions.propTypes = {
  handleImportCSV: PropTypes.func.isRequired,
  stopRenderImport: PropTypes.func.isRequired,
  history: PropTypes.object.isRequired
};
