// these are the params that are safe to purge to prevent us removing none filter related params
const paramsSafeToPurge = ['tax_query[]', 's', 'orderby', 'order', 'pn'];

// Add event listener for first load
window.addEventListener('load', () => {
  const container = document.getElementById('ajax-filterable');
  if (!container) {
    return;
  }
  process_filters(container);
  process_pagination(container);
  process_sorting(container);
  initPaginationControls();

  // Initialize canonical URL on page load if meta tag exists
  const initialPageMeta = document.querySelector(
    'meta[name="initial-page-num"]'
  );
  if (initialPageMeta) {
    const initialPage = parseInt(initialPageMeta.getAttribute('content'));
    updateCanonicalUrl(initialPage);
  }
});

// Process filters
function process_filters(container) {
  // FILTER PROCESSING
  // Take all filters in a single array and process
  const filters = document.querySelectorAll(
    '.filter-group:not(.filter-sorting)'
  );

  for (i = 0; i < filters.length; i++) {
    filters[i].addEventListener('input', (event) => {
      // if text input then make sure it has more than 3 characters
      if (
        event.target.type == 'text' &&
        event.target.value.length < 3 &&
        event.target.value.length !== 0
      )
        return;

      // Look for all possible filters on the DOM
      const allFilters = document.querySelectorAll('.filter-group');
      // Collect updated filter parameters
      let parameters = collectFilterParameters(allFilters);

      // Modify parameters to query args
      query_args = modifyParametersToQueryArgs(parameters);

      // Create loading state element
      createLoadingState(container);

      // Update URL
      updateUrl(query_args, paramsSafeToPurge);

      // Reload container part, which automatically remove loading state by overriding
      reloadResult(container, query_args);

      // Scroll to the top of the page after every filter state change
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    });
  }
}

// Process filters
function process_sorting(container) {
  // FILTER PROCESSING
  // Take all filters in a single array and process

  const filters = document.querySelectorAll('.filter-sorting');

  for (i = 0; i < filters.length; i++) {
    filters[i].addEventListener('click', (event) => {
      event.preventDefault();

      const filterGroup = event.target.closest('.filter-sorting');
      const button = filterGroup.querySelector('button');
      const filterItems = filterGroup.querySelectorAll('.filter-item');
      const checkedFilterItem = filterGroup.querySelector(
        '.filter-item:checked'
      );

      filterItems.forEach((item) => {
        // check if the item is the currently checked item
        if (item == checkedFilterItem) {
          // uncheck the item if currently checked
          item.checked = false;
        } else {
          // check the item if not currently checked and dispatch an input event to trigger the filtering process
          item.checked = true;
        }
      });
      // rotate the button
      button.classList.toggle('rotate-180');

      // Look for all possible filters on the page
      const allFilters = document.querySelectorAll('.filter-group');
      // Collect updated filter parameters
      let parameters = collectFilterParameters(allFilters);

      // Modify parameters to query args
      query_args = modifyParametersToQueryArgs(parameters);

      // Create loading state element
      createLoadingState(container);

      // Update URL
      updateUrl(query_args, paramsSafeToPurge);

      // Reload container part, which automatically remove loading state by overriding
      reloadResult(container, query_args);
    });
  }
}

// Process Pagination
function process_pagination(container) {
  // PAGINATION PROCESSING
  const paginators = document.querySelectorAll('.pagination-trigger');
  const filters = document.querySelectorAll('.filter-group');

  for (i = 0; i < paginators.length; i++) {
    paginators[i].addEventListener('input', (event) => {
      let page_num = event.target;
      let pagination_args = addPaginationQueryArg(page_num.value);

      // Collect updated filter parameters
      let filter_parameters = collectFilterParameters(filters);

      // Modify parameters to query args
      filter_args = modifyParametersToQueryArgs(filter_parameters);

      // Join 2 objects
      Object.assign(pagination_args, filter_args);

      // Create loading state element
      createLoadingState(container);

      // Update URL
      updateUrl(pagination_args, paramsSafeToPurge);

      // Reload container part, which automatically remove loading state by overriding
      reloadResult(container, pagination_args);

      // Scroll to the top of the page after every filter state change
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    });
  }
}

// Collect all parameters from filterbar
function collectFilterParameters(filters) {
  // Create empty object to get parameters from filter items
  let parameters = [];

  for (i = 0; i < filters.length; i++) {
    let parameter = {};
    let filter = filters[i];
    let template = filter.dataset.template;
    let filterValuesBySlug = {};

    // if search
    if (template == 'search') {
      parameter.s = filter.value;
    }

    // Collect value from classic select tag filter item
    if (template == 'select') {
      if (filter.value) {
        if (filter.dataset.name == 'orderby') {
          params = new URLSearchParams(filter.value);
          parameter.orderby = params.get('orderby');
          parameter.order = params.get('order');
          if (params.get('orderby') == 'meta_value') {
            parameter['meta_key'] = params.get('meta_key');
          }
          parameter.values = false;
        } else {
          parameter.slug = filter.dataset.name;
          parameter.values = filter.value;
        }
      }
    }

    // Collect value from checkboxes / buttons
    if (template == 'checkbox' || template == 'radio') {
      filter.querySelectorAll('.filter-item:checked').forEach((item) => {
        if (filterValuesBySlug[item.name] == undefined)
          filterValuesBySlug[item.name] = [];
        filterValuesBySlug[item.name].push(item.value);
      });
    }

    // collect value from toggle radio fields
    if (template == 'toggle') {
      filter.querySelectorAll('.filter-item:checked').forEach((item) => {
        if (item.name == 'orderby') {
          params = new URLSearchParams(item.value);
          parameter.orderby = params.get('orderby');
          parameter.order = params.get('order');
          if (params.get('orderby') == 'meta_value') {
            parameter['meta_key'] = params.get('meta_key');
          }
          parameter.values = false;
        } else {
          parameter.slug = item.name;
          parameter.values = item.value;
        }
      });
    }

    // Collect filter types
    parameter.type = filter.dataset.type;

    // Collect filter compare (for meta filters)
    if (filter.dataset.compare) {
      parameter.compare = filter.dataset.compare;
    }

    // Push everything to params array
    if (Object.keys(filterValuesBySlug).length > 0) {
      for (const [key, value] of Object.entries(filterValuesBySlug)) {
        parameters.push({
          slug: key,
          values: value,
          type: parameter.type,
          compare: parameter.compare,
        });
      }
    } else {
      parameters.push(parameter);
    }
  }
  return parameters;
}

// Convert recieved field parameters to final query args which match WP_Query standards
function modifyParametersToQueryArgs(data) {
  if (!data) {
    return;
  }

  let args = {};

  for (let i = 0; i < data.length; i++) {
    const parameter = data[i];

    // Work with taxonomy filters
    if (parameter.type == 'taxonomy') {
      if (!parameter.hasOwnProperty('values')) continue;

      // skip if no values
      if (parameter.values.length == 0) continue;

      // If we have Show all button with value = 0, skip this item in a loop
      // It was declared in filters-all.php on line 67
      if (parameter.values[0] == 0) continue;

      // create tax query arg if not exists
      if (args.tax_query == undefined) args.tax_query = [];

      // push new tax query to args array of tax queries
      args.tax_query.push({
        taxonomy: parameter.slug,
        field: 'slug',
        terms: parameter.values,
      });
    }

    // Work with meta filters
    if (parameter.type == 'meta') {
      if (parameter.values.length == 0) {
        continue;
      }

      args.meta_query = [
        {
          key: parameter.slug,
          value: parameter.values,
          compare: parameter.compare,
        },
      ];
    }

    // Work with meta filters
    if (parameter.type == 'orderby') {
      if (parameter.orderby) args.orderby = parameter.orderby;
      if (parameter.order) args.order = parameter.order;
      if (parameter.orderby == 'meta_value') {
        args.meta_key = parameter.meta_key;
      }
    }

    if (parameter.type == 'search' && parameter.s) args.s = parameter.s;
  }
  return args;
}

// Make WP_Query vars from paginations form
function addPaginationQueryArg(page_number) {
  let args = {};
  args.paged = page_number;
  return args;
}

// Update canonical URL in head based on current page
function updateCanonicalUrl(pageNum) {
  // Get the current canonical link element or create one if it doesn't exist
  let canonicalLink = document.querySelector('link[rel="canonical"]');

  if (!canonicalLink) {
    canonicalLink = document.createElement('link');
    canonicalLink.rel = 'canonical';
    document.head.appendChild(canonicalLink);
  }

  // Set the canonical URL based on the page number
  if (pageNum === 1) {
    // For the first page, use the main archive URL
    canonicalLink.href = `${window.location.protocol}//${window.location.host}/blog/`;
  } else {
    // For paginated pages, use the paginated URL
    canonicalLink.href = `${window.location.protocol}//${window.location.host}/blog/page/${pageNum}/`;
  }
}

// Main AJAX call to reload page
async function reloadResult(container, parameters) {
  // Looking if we got parameters array
  if (!parameters) {
    return;
  }

  // Looking for template part
  let template_part = container.dataset.templatePart;
  // If exist - create json object and merge with other parameters
  if (template_part) {
    let template_part_obj = {
      template_part: template_part,
    };
    Object.assign(parameters, template_part_obj);
  } else {
    // If doesn't exist, just finish propagation
    return;
  }

  const ajaxURL = `${siteUrl}/wp-admin/admin-ajax.php?action=ajax_filter_posts`;
  try {
    const response = await fetch(ajaxURL, {
      method: 'POST',
      credentials: 'same-origin',
      body: JSON.stringify(parameters),
    });
    if ((content = await response.text())) {
      container.innerHTML = content;

      // Re-init pagination next/prev controls event listeners
      initPaginationControls();

      // Reload pagination processing because pagination elements was reloaded and currently out of the DOM
      process_pagination(container);

      // Reload filters processing because filters elements was reloaded and currently out of the DOM
      process_filters(container);

      // Reload filters processing because filter elements was reloaded and currently out of the DOM (at least sort by date)
      process_sorting(container);

      // Update canonical URL based on current page
      if (parameters.paged) {
        updateCanonicalUrl(parseInt(parameters.paged));
      }

      // check if filters are in the right place once the content has been reloaded
      setTimeout(() => filtersPosition(), 500);
    }
  } catch (error) {
    console.error(error);
    return;
  }
}

const createLoadingState = (element) => {
  const loaderOverlay = document.createElement('div');
  loaderOverlay.classList.add(
    'inset-0',
    'absolute',
    'opacity-70',
    'bg-white',
    'flex',
    'items-center',
    'justify-center'
  );
  element.appendChild(loaderOverlay);
};

// Add event listener for pagination next / prev buttons
function initPaginationControls() {
  const container = document.getElementById('pagination');

  if (!container) {
    return;
  }

  let buttons = container.querySelectorAll('.pagination-control');
  let inputs = container.querySelectorAll('.pagination-trigger');

  for (let i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener('click', (event) => {
      event.preventDefault();

      let current = false;

      for (let n = 0; n < inputs.length; n++) {
        if (inputs[n].checked) {
          current = inputs[n];
        }
      }

      if (current) {
        let parent = current.parentElement;

        if (parent.previousElementSibling) {
          let prev = parent.previousElementSibling.firstElementChild;
          if (prev && event.currentTarget.id == 'pagination-prev') {
            prev.click();
            return;
          }
        }

        if (parent.nextElementSibling) {
          let next = parent.nextElementSibling.firstElementChild;
          if (next && event.currentTarget.id == 'pagination-next') {
            next.click();
            return;
          }
        }
      }
    });
  }
}

// update the url with the query args
function updateUrl(args, paramsSafeToPurge) {
  let url = new URL(window.location.href);
  // remove all query params that are safe to purge
  for (const key of url.searchParams.keys()) {
    if (paramsSafeToPurge.includes(key)) url.searchParams.delete(key);
  }

  // loop through args and set url params for them

  // Find base url (remove everything after pagination keyword - split to parts and get only first part)
  for (let [key, value] of Object.entries(args)) {
    // if tax query then we'll manually set it to make it cleaner
    if (key == 'tax_query') {
      url.searchParams.delete('tax_query[]');
      value.forEach((taxQueryItem) => {
        url.searchParams.append(
          'tax_query[]',
          JSON.stringify({
            tax: taxQueryItem.taxonomy,
            terms: taxQueryItem.terms,
          })
        );
      });
      continue;
    }

    // Change key for URL string from "paged" to "pn" because WP uses 2 different keys in URL string and in WP Query args
    if (key == 'paged') {
      //key = "pn";
      key = false;
      if (url.href.includes('/page/')) {
        if (value > 1) {
          url.href = url.href.replace(
            /\/page\/([0-9]|[0-9][0-9])+/,
            '/page/' + value
          );
        } else {
          url.href = url.href.replace(/\/page\/([0-9]|[0-9][0-9])+/, '');
        }
      } else {
        if (value > 1) {
          url.pathname = url.pathname + 'page' + '/' + value + '/';
        } else {
          url.href = url.href.replace(/\/page\/([0-9]|[0-9][0-9])+/, '');
        }
      }

      // Small fix for Title
      document.title = document.title.replace(
        /Page ([0-9]|[0-9][0-9])+/,
        'Page ' + value
      );
    }

    // if object then stringify it
    if (typeof value === 'object') {
      value = JSON.stringify(value);
    }

    // set the url param
    if (key && value) {
      url.searchParams.set(key, value);
    }
  }

  // push the new url to the history
  window.history.pushState({}, '', url);

  // Update canonical URL based on page number
  if (args.paged) {
    updateCanonicalUrl(parseInt(args.paged));
  } else {
    // Default to page 1 if no page number specified
    updateCanonicalUrl(1);
  }
}

// Process form Submit (called inline at <form> tag)
function process_form_submit(event, containerId) {
  event.preventDefault();

  const container = document.getElementById(containerId);
  if (!container) {
    return;
  }

  // FILTER PROCESSING
  // Take all filters in a single array and process
  const filters = document.querySelectorAll(
    '.filter-group:not(.filter-sorting)'
  );

  for (i = 0; i < filters.length; i++) {
    // Look for all possible filters on the DOM
    const allFilters = document.querySelectorAll('.filter-group');
    // Collect updated filter parameters
    let parameters = collectFilterParameters(allFilters);

    // Modify parameters to query args
    query_args = modifyParametersToQueryArgs(parameters);

    // Update URL
    updateUrl(query_args, paramsSafeToPurge);

    // Reload container part, which automatically remove loading state by overriding
    reloadResult(container, query_args);

    // Scroll to the top of the page after every filter state change
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  }
}

// Move the filters to the top of the page on mobile and to the sidebar on desktop
function filtersPosition() {
  const container = document.getElementById('ajax-filterable');
  const desktopFiltersWrapper = document.querySelector(
    '.desktop-filters-wrapper'
  );
  const mobileFiltersWrapper = document.querySelector(
    '.mobile-filters-wrapper'
  );
  if (!desktopFiltersWrapper || !mobileFiltersWrapper) return;

  if (!isContentsEmpty(desktopFiltersWrapper))
    checkScreenSize('smaller', 1024, () => {
      moveContents(desktopFiltersWrapper, mobileFiltersWrapper);
      process_filters(container);
    });

  if (!isContentsEmpty(mobileFiltersWrapper))
    checkScreenSize('larger', 1024, () => {
      moveContents(mobileFiltersWrapper, desktopFiltersWrapper);
      process_filters(container);
    });
}
window.addEventListener('load', filtersPosition);
window.addEventListener('resize', filtersPosition);
