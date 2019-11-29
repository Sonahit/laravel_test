import { getIndex } from './filterTable';

export default function sortTable(table, sortOptions) {
  const { dataSort, asc } = sortOptions;
  if (!dataSort) return table;
  const comparer = asc => (a, b) =>
    ((v1, v2) =>
      v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2)
        ? v1 - v2
        : v1.toString().localeCompare(v2))(asc ? a : b, asc ? b : a);
  const [index, subIndex] = getIndex(dataSort);
  return table.sort((trA, trB) => {
    if (subIndex) {
      const vA = trA[index][subIndex];
      const vB = trB[index][subIndex];
      return comparer(!asc)(vA, vB);
    }
    const vA =
      index === 'delta' ? trA.plan_attributes.price - trA.fact_attributes.price : trA[index];
    const vB =
      index === 'delta' ? trB.plan_attributes.price - trB.fact_attributes.price : trB[index];
    return comparer(asc)(vA, vB);
  });
}

function toAsc(node) {
  node.classList.remove('desc');
  node.classList.add('asc');
}

function toDesc(node) {
  node.classList.remove('asc');
  node.classList.add('desc');
}

function active(node) {
  const prevActive = Array.from(document.getElementsByClassName('active'));
  if (prevActive) {
    prevActive.forEach(prev => prev.classList.remove('active'));
  }
  node.classList.toggle('active');
}

export function changeArrowDirection(e) {
  const node = e.target;
  const parent = node.parentNode;
  let asc = false;
  if (parent.classList.contains('main-table__th--sortable')) {
    if (node.classList.contains('asc')) {
      if (node.classList.contains('active')) {
        asc = false;
        toDesc(node);
      } else {
        asc = true;
        toAsc(node);
      }
    } else if (
      node.classList.contains('desc') &&
      parent.classList.contains('main-table__th--sortable')
    ) {
      if (node.classList.contains('active')) {
        asc = true;
        toAsc(node);
      } else {
        asc = false;
        toDesc(node);
      }
    }
    active(node);
  } else if (node.classList.contains('main-table__th--sortable')) {
    const child = node.children[0];
    if (child.classList.contains('desc')) {
      if (child.classList.contains('active')) {
        asc = true;
        toAsc(child);
      } else {
        asc = false;
        toDesc(child);
      }
    } else if (child.classList.contains('asc')) {
      if (child.classList.contains('active')) {
        asc = false;
        toDesc(child);
      } else {
        asc = true;
        toAsc(child);
      }
    }
    active(child);
  }
  return asc;
}
