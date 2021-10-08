#!/usr/bin/env bash
# set -e
# set -x

main() {
	local READONLY SCSS_PATH="./resources/scss"
	local READONLY CSS_PATH="./public/assets/css"

	local scss_files=($(find . -name '*.scss' -type f))
	local file_path=""

	[[ -d "${CSS_PATH}" ]] || mkdir -p "${CSS_PATH}"

	for f in "${scss_files[@]}"; do
		file_path=${f##${SCSS_PATH}}
		sass "${f}" "${CSS_PATH}/"${file_path%.*}.css""
	done

}

main $@
exit 0
