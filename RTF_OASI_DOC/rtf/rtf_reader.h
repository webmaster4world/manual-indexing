/***********************************************************************
 *
 * Copyright (C) 2010, 2011 Graeme Gott <graeme@gottcode.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 ***********************************************************************/
 
 /*
  *  code from this reader class is part from focuswriter-1.3.3
  *  kword from kde , but is not complete and is unable to set color and style 
  *  from original rtf file.
  * 
  * */
 
 

#ifndef RTF_READER_H
#define RTF_READER_H

#include "tokenizer.h"

#include <QCoreApplication>
#include <QStack>
#include <QTextBlockFormat>
#include <QTextCharFormat>
#include <QTextCursor>
class QString;
class QTextEdit;

namespace RTF
{
	class Reader
	{
		Q_DECLARE_TR_FUNCTIONS(Reader)

	public:
		Reader();

		QString errorString() const;
		bool hasError() const;

		void read(QIODevice* device, QTextDocument* text);

	private:
		void endBlock(qint32);
		void insertColor(qint32);
		void ignoreGroup(qint32);
		void insertBlock(qint32);
		void insertHexSymbol(qint32);
		void insertSymbol(qint32 value);
		void insertUnicodeSymbol(qint32 value);
		void pushState();
		void popState();
		void resetBlockFormatting(qint32);
		void resetTextFormatting(qint32);
		void setBlockAlignment(qint32 value);
		void setBlockDirection(qint32 value);
		void setBlockIndent(qint32 value);
		void setTextBold(qint32 value);
		void setTextItalic(qint32 value);
		void setTextStrikeOut(qint32 value);
		void setTextUnderline(qint32 value);
		void setTextVerticalAlignment(qint32 value);
		void setSkipCharacters(qint32 value);
		void setCodepage(qint32 value);
		void setCodepageMac(qint32);
		void setFont(qint32 value);
		void setFontCharset(qint32 value);
		void setFontCodepage(qint32 value);

	private:
		Tokenizer m_token;
		bool m_in_block;

		struct State
		{
			QTextBlockFormat block_format;
			QTextCharFormat char_format;
			bool ignore_control_word;
			bool ignore_text;
			int skip;
			int active_codepage;
		};
		QStack<State> m_states;
		State m_state;

		QTextCodec* m_codec;
		QTextCodec* m_codepage;
		QVector<QTextCodec*> m_codepages;

		QString m_error;

		QTextDocument* m_text;
		QTextCursor m_cursor;
	};
}

#endif
