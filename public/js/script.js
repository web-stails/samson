let
    io_res = data => {
        if(data === "Unauthorized" || data.message === "Unauthenticated.") {
            location.reload();
            return false;
        }

        return true;
    },
    is_419_code = status => {
        if(/419/i.test(status)) {
            location.reload();
        }
        if(/401/i.test(status)) {
            location.reload();
        }
        if(/403/i.test(status)) {
            location.reload();
        }
    },
    get = (url, data_send = null) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                data: {
                    _method: 'GET',
                    _token: $("meta[name='csrf-token']").attr("content"),
                    ...data_send
                },
                type: 'GET',
                dataType: 'JSON',
                cache: false,
                success: data => {
                    if (! io_res(data)) {
                        reject(data);
                    } else {
                        resolve(data);
                    }
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    let error_obj = {
                        jqXHR,
                        textStatus,
                        errorThrown,
                    };

                    console.log(error_obj);

                    io_res(errorThrown)
                    is_419_code(jqXHR.status);

                    reject(error_obj);
                }
            });
        })
    },
    post = (url, data_send = null, files = {}) => {
        return new Promise((resolve, reject) => {
            if(! empty(files)) {
                let data = new FormData();
                data.append('_method','post');
                data.append('_token', $("meta[name='csrf-token']").attr("content"));

                forIn(files, (input_file, name) => {
                    data.append(name, $(input_file).prop('files')[0]);
                })

                data_send && forIn(data_send, (value, name) => {
                    data.append(name, value);
                })

                $.ajax({
                    url,
                    data,
                    type: 'POST',
                    cache: false,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success(data) {
                        if(! io_res(data)) {
                            reject(data);
                        } else {
                            resolve(data);
                        }
                    },
                    error(xhr, ajaxOptions, thrownError) { // принимает this
                        if(io_res(xhr.responseJSON)) {
                            reject(new Error(xhr.status + ': ' + thrownError))
                        }
                    }
                });
            } else {
                $.ajax({
                    url,
                    data: {
                        _method: 'post',
                        _token: $("meta[name='csrf-token']").attr("content"),
                        ...data_send
                    },
                    type: 'POST',
                    dataType: 'JSON',
                    cache: false,
                    success: data => {
                        if (! io_res(data)) {
                            reject(data);
                        } else {
                            resolve(data);
                        }
                    },
                    error(jqXHR, textStatus, errorThrown) {
                        reject(jqXHR.status + ': ' + errorThrown);
                    }
                });
            }
        })
    }
;
