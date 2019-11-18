import TableHelper from './TableHelper';
import ApiHelper from './ApiHelper';

const tableHelper = new TableHelper();
function getOS() {
  const { userAgent } = window.navigator;
  const { platform } = window.navigator;
  const macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'];
  const windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'];
  const iosPlatforms = ['iPhone', 'iPad', 'iPod'];
  let os = null;

  if (macosPlatforms.indexOf(platform) !== -1) {
    os = 'Mac OS';
  } else if (iosPlatforms.indexOf(platform) !== -1) {
    os = 'iOS';
  } else if (windowsPlatforms.indexOf(platform) !== -1) {
    os = 'Windows';
  } else if (/Android/.test(userAgent)) {
    os = 'Android';
  } else if (!os && /Linux/.test(platform)) {
    os = 'Linux';
  }

  return os;
}

export default class DownloadHelper {
  prepareHeaders(table) {
    if (!table) return [];
    const tHead = Array.from(table.rows).filter(v => v.rowIndex <= 1);
    // const tBody = Array.from(table.rows).filter(v => v.rowIndex > 1);
    const [main, sub] = [Array.from(tHead[0].children), Array.from(tHead[1].children)];
    for (let i = 0; i < main.length - 1; ++i) {
      if (main[i].rowSpan === 2) {
        sub.unshift('');
      }
      if (main[i].colSpan === 2) {
        main.splice(i + 1, 0, '');
      }
    }
    return [main, sub];
  }

  toCsv(rawHead, rawBody) {
    const head = [];
    const body = [];
    rawHead.forEach(h => head.push(h.join(';')));
    rawBody.forEach(b => body.push(b.join(';')));
    return head.concat(body).join('\n');
  }

  downloadCSV(button) {
    button.classList.toggle('processing');
    const table = tableHelper.getTable();

    // If no table return

    if (!table) return;
    const tHead = this.prepareHeaders(table);

    const api = new ApiHelper();
    api.get('/billed_meals', [{ key: 'paginate', value: '-1' }]).then(({ pages }) => {
      const tBody = pages.map(meal => {
        // eslint-disable-next-line camelcase
        const { id, date, type, plan_attributes, fact_attributes } = meal;
        const nomClass = meal.class;
        const delta = (plan_attributes.price - fact_attributes.price).toFixed(2);
        return [
          id,
          date,
          nomClass,
          type,
          plan_attributes.codes.join(', ') || 'NO DATA',
          fact_attributes.codes.join(', '),
          plan_attributes.qty,
          fact_attributes.qty,
          plan_attributes.price.toFixed(2),
          fact_attributes.price.toFixed(2),
          delta
        ];
      });
      button.classList.toggle('processing');
      const csv = this.toCsv(tableHelper.values(tHead), tBody);
      const charset = getOS()
        .toLowerCase()
        .includes('windows')
        ? 'windows-1251'
        : 'utf-8';
      this.download(csv, 'csv.csv', `data:text/csv;charset=${charset}`);
    });
  }

  downloadPDF() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(
      `${location.origin}/api/v1/pdf?pagination=${sessionStorage.getItem('pagination') *
        sessionStorage.getItem('page') || 20}&page=${sessionStorage.getItem('page') || 1}`,
      {
        method: 'GET',
        headers: {
          'X-CSRF-TOKEN': token,
          Cookie: document.cookie
        }
      }
    )
      .then(data => data.text())
      .then(data => this.download(data, 'pdf.pdf', 'data:application/pdf'))
      .catch(err => {
        throw err;
      });
  }

  downloadXML() {
    const table = TableHelper.prototype.getTable();
    const xml = new XMLSerializer();
    const xmlTable = xml.serializeToString(table);
    this.download(xmlTable, 'xml', 'data:text/xml');
  }

  download(data, name, type) {
    const download = document.createElement('a');
    download.style.display = 'none';
    download.download = name;
    document.body.appendChild(download);
    download.href = `${type},${data}`;
    download.click();
    document.body.removeChild(download);
  }

  tableToCsv() {
    const table = tableHelper.getTable();
    if (!table) return;

    const tHead = this.prepareHeaders(table);
    const tBody = Array.from(table.rows).filter(v => v.rowIndex > 1);

    const csv = this.toCsv(tableHelper.values(tHead), tableHelper.values(tBody));
    const charset = getOS()
      .toLowerCase()
      .includes('windows')
      ? 'windows-1251'
      : 'utf-8';
    this.download(csv, 'csv.csv', `data:text/csv;charset=${charset}`);
  }
}
